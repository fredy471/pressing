export default function orderForm(services) {
    return {
        services: services,
        openService: services.length > 0 ? services[0].id : null,
        selections: {},
        prices: {},
        clothesTypesPerService: {},
        loadingServiceId: null,

        deliveryMode: 'depot_physique',
        pickup_address: '',
        pickup_phone: '',

        init() {
            if (this.services.length > 0) {
                this.loadServiceData(this.services[0].id);
            }
        },

        toggleService(serviceId) {
            this.openService = this.openService === serviceId ? null : serviceId;
            if (this.openService) {
                this.loadServiceData(serviceId);
            }
        },

        loadError: null,

        async loadServiceData(serviceId, attempt = 1) {
            if (this.clothesTypesPerService[serviceId]) return;

            this.loadingServiceId = serviceId;
            this.loadError = null;
            try {
                const response = await fetch(`/api/services/${serviceId}/prices`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    // 502/503/504 : instance Render en cours de réveil → nouvelle tentative
                    if ([502, 503, 504].includes(response.status) && attempt < 4) {
                        await new Promise(r => setTimeout(r, attempt * 3000));
                        return await this.loadServiceData(serviceId, attempt + 1);
                    }
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                this.clothesTypesPerService[serviceId] = data.clothes_types ?? [];

                if (!this.prices[serviceId]) this.prices[serviceId] = {};
                Object.entries(data.prices ?? {}).forEach(([id, price]) => {
                    this.prices[serviceId][id] = Number(price) || 0;
                });
            } catch (error) {
                console.error('Erreur lors du chargement des tarifs', error);
                this.loadError = 'Impossible de charger les tarifs. Réessayez dans un instant.';
            } finally {
                this.loadingServiceId = null;
            }
        },

        getQuantity(serviceId, typeId) {
            return this.selections[serviceId]?.[typeId] || 0;
        },

        updateQuantity(serviceId, typeId, qty) {
            if (!this.selections[serviceId]) {
                this.selections[serviceId] = {};
            }
            let parsedQty = parseInt(qty) || 0;
            if (parsedQty < 0) parsedQty = 0;

            if (parsedQty === 0) {
                delete this.selections[serviceId][typeId];
                if (Object.keys(this.selections[serviceId]).length === 0) {
                    delete this.selections[serviceId];
                }
            } else {
                this.selections[serviceId][typeId] = parsedQty;
            }
        },

        getServiceItemsCount(serviceId) {
            if (!this.selections[serviceId]) return 0;
            return Object.values(this.selections[serviceId]).reduce((a, b) => a + b, 0);
        },

        get totalItems() {
            let total = 0;
            for (let sId in this.selections) {
                for (let tId in this.selections[sId]) {
                    total += this.selections[sId][tId];
                }
            }
            return total;
        },

        get totalPrice() {
            let total = 0;
            for (let sId in this.selections) {
                for (let tId in this.selections[sId]) {
                    let qty = this.selections[sId][tId];
                    let price = this.prices[sId]?.[tId] || 0;
                    total += qty * price;
                }
            }
            return total;
        },

        get canSubmit() {
            return this.totalItems > 0 &&
                (this.deliveryMode !== 'collecte_livraison' || (this.pickup_address.trim() !== '' && this.pickup_phone.trim() !== ''));
        },

        formatPrice(value) {
            return new Intl.NumberFormat('fr-FR').format(value || 0);
        },

        beforeSubmit() { }
    }
}
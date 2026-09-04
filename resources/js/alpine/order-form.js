export default (services) => ({
    services: services,
    serviceId: null,
    deliveryMode: null,
    pickupAddress: '',
    pickupPhone: '',
    clothesTypes: [],
    prices: {},
    quantities: {},
    loading: false,

    get activeServiceName() {
        const service = this.services.find(s => s.id === this.serviceId);
        return service ? service.name : '';
    },

    async selectService(id) {
        this.serviceId = id;
        await this.loadClothesTypes();
    },

    async loadClothesTypes() {
        this.loading = true;
        this.clothesTypes = [];
        this.quantities = {};

        const response = await fetch(`/commander/${this.serviceId}/vetements`);
        const data = await response.json();

        this.clothesTypes = data.clothes_types;
        this.prices = data.prices;

        this.clothesTypes.forEach(type => {
            this.quantities[type.id] = 0;
        });

        this.loading = false;
    },

    formatPrice(value) {
        return new Intl.NumberFormat('fr-FR').format(value || 0);
    },

    get totalPrice() {
        return Object.entries(this.quantities).reduce((sum, [typeId, qty]) => {
            return sum + (this.prices[typeId] || 0) * qty;
        }, 0);
    },

    get totalItems() {
        return Object.values(this.quantities).reduce((sum, qty) => sum + qty, 0);
    },

    get canSubmit() {
        return this.serviceId
            && this.deliveryMode
            && this.totalItems > 0
            && (this.deliveryMode !== 'collecte_livraison' || this.pickupAddress.trim() !== '');
    },

    beforeSubmit(event) {
        if (!this.canSubmit) {
            event.preventDefault();
        }
    },
});
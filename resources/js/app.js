

import Alpine from 'alpinejs';
import orderForm from './alpine/order-form.js';
import AOS from 'aos';
import 'aos/dist/aos.css';

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 1000,
        once: false,
        offset: 100,
        easing: 'ease-in-out',
    });
});

window.Alpine = Alpine;
Alpine.data('orderForm', orderForm);
Alpine.start();

window.toggleMobileNav = () => {
    const nav = document.getElementById('mobileNav');
    if (!nav) return;

    nav.classList.toggle('max-h-0');
    nav.classList.toggle('max-h-screen');
    nav.classList.toggle('opacity-0');
    nav.classList.toggle('opacity-100');
    nav.classList.toggle('scale-y-95');
    nav.classList.toggle('scale-y-100');
    nav.classList.toggle('pointer-events-none');

    const btn = document.querySelector('[aria-label="Menu"]');
    if (btn) {
        btn.setAttribute('aria-expanded', nav.classList.contains('opacity-100') ? 'true' : 'false');
    }
};

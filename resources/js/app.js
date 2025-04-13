import './bootstrap';
import './polyfills';
import './assets'; // Importer les assets pour s'assurer qu'ils sont disponibles
import './phone-input'; // Importer la configuration pour intl-tel-input
import './adblock-detector';

// Import des logos pour s'assurer qu'ils sont disponibles via Vite
import.meta.glob([
    '../images/**',
]);

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

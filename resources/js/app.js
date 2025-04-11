import './bootstrap';

import Alpine from 'alpinejs';
import './assets'; // Importer les assets pour s'assurer qu'ils sont disponibles
import './phone-input'; // Importer la configuration pour intl-tel-input

// Import des logos pour s'assurer qu'ils sont disponibles via Vite
import.meta.glob([
    '../images/**',
]);

window.Alpine = Alpine;

Alpine.start();

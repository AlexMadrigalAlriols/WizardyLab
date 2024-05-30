import { createApp } from 'vue';
import './bootstrap'; // Asegúrate de que este archivo exista y se cargue correctamente

// Importa el componente
import MarketplaceRule from './components/BoardRule/Index.vue';

// Crea la instancia de la aplicación Vue
const app = createApp({});

// Registra el componente
app.component('marketplace-rule', MarketplaceRule);

// Monta la aplicación
app.mount('#app');

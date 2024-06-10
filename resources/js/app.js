import { createApp } from 'vue';
import './bootstrap'; // Asegúrate de que este archivo exista y se cargue correctamente

// Importa el componente
import BoardRule from './components/BoardRule/Index.vue';
import MultiAssignment from './components/MultiAssignment/Index.vue';

// Crea la instancia de la aplicación Vue
const app = createApp({});

// Registra el componente
app.component('marketplace-rule', BoardRule);
app.component('multi-assignment', MultiAssignment);

// Monta la aplicación
app.mount('#app');

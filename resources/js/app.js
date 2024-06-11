import { createApp } from 'vue';
import i18n from './i18n';

import './bootstrap'; // Asegúrate de que este archivo exista y se cargue correctamente

// Importa los componentes
import BoardRule from './components/BoardRule/Index.vue';
import MultiAssignment from './components/MultiAssignment/Index.vue';
import VueSelect from 'vue-select';
import 'vue-select/dist/vue-select.css'; // Importa los estilos de vue-select

// Crea la instancia de la aplicación Vue
const app = createApp({});

// Usa el i18n configurado
app.use(i18n);

// Registra los componentes
app.component('marketplace-rule', BoardRule);
app.component('multi-assignment', MultiAssignment);
app.component('v-select', VueSelect);

// Monta la aplicación
app.mount('#app');

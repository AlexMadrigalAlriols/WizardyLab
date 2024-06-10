import { createApp } from 'vue';
import './bootstrap'; // Asegúrate de que este archivo exista y se cargue correctamente

// Importa el componente
import BoardRule from './components/BoardRule/Index.vue';
import MultiAssignment from './components/MultiAssignment/Index.vue';
import VueSelect from 'vue-select';
import 'vue-select/dist/vue-select.css'; // Importa los estilos de vue-select

// Crea la instancia de la aplicación Vue
const app = createApp({});

// Registra el componente
app.component('marketplace-rule', BoardRule);
app.component('multi-assignment', MultiAssignment);
app.component('v-select', VueSelect);


// Monta la aplicación
app.mount('#app');

import '../resources/css/app.css';
import '../resources/js/bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/js/index.esm';
import '@popperjs/core';
import 'vue3-loading-overlay/dist/vue3-loading-overlay.css';
import $ from 'jquery';
import App from '../src/App.vue';
import Login from './components/LoginComponent.vue';
import moment from 'moment';
import router from './router';
import './interceptors/axios';
import vSelect from "vue-select";
import 'vue-final-modal/style.css';
import _ from 'underscore';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-responsive-bs5';
import 'bootstrap-icons/font/bootstrap-icons.min.css';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

import { createApp } from 'vue';

const app = createApp();

app.provide('message', 'you are now connected!');

DataTable.use(DataTablesCore);
app.component('app', App);
app.component('login', Login);
app.component("v-select", vSelect);
app.use($);
app.use(router);
app.use(moment);
app.use(_);
app.use(PrimeVue, {
    // Default theme configuration
    theme: {
        preset: Aura,
        options: {
            prefix: 'p',
            darkModeSelector: false || 'none',
            cssLayer: false
        }
    }
});
app.mount("#app");
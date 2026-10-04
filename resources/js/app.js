/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


import './flowbite-init';
import './theme-toggle';



// vue-2 version setup
// window.Vue = require('vue').default;
// import router from './router'

// Vue.component('employee-index', require('./pages/Employee/Index.vue').default);

// const app = new Vue({
//     el: '#app',
//     router
// });



// // sidebar script
// import { initSidebar } from './sidebar';
// initSidebar();


// vue-3 version setup
import { createApp } from 'vue';
import router from './router';
import EmployeeIndex from './pages/Employee/Index.vue';

// sidebar script (plain JS, framework-agnostic)
import { initSidebar } from './sidebar';
initSidebar();

const app = createApp({});

// Register global components if you still need them
app.component('employee-index', EmployeeIndex);

app.use(router);
app.mount('#app');
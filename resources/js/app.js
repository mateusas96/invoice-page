/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Fire = new Vue();

import { Form, HasError, AlertError } from 'vform';

Vue.component('pagination', require('laravel-vue-pagination'));

window.Form = Form;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    thickness: '5px'
  })
  
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';  
import Swal from 'sweetalert2';
window.Swal = Swal;
Vue.use(VueSweetalert2);

import VueRouter from 'vue-router';
Vue.use(VueRouter)

let routes = [
    { path: '/users-management', component: require('./components/Users.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/home', component: require('./components/Home.vue').default},
    { path: '', component: require('./components/Home.vue').default},
    { path: '*', component: require('./components/PageNotFound.vue').default},
    { path: '/send-invoice', component: require('./components/SendInvoice.vue').default},
    { path: '/my-invoices', component: require('./components/MyInvoices.vue').default},
  ]


  const router = new VueRouter({
      mode: 'history',
    routes
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});

import Vue from 'vue'
import App from './App.vue'
import vuetify from "./plugins/vuetify";
import VueRouter from "vue-router";
import 'vuetify/dist/vuetify.min.css';
import {getRoutes} from "./Routes";
import Notifications from "vue-notification";

/**
 * Creating the base router
 */
const router = new VueRouter({
    routes: getRoutes(),
    mode: 'history'
});

/**
 * Enabling router
 */
Vue.use(VueRouter);
/**
 * Enabeling notification plugin
 */
Vue.use(Notifications);

new Vue({
    vuetify,
    router,
    render: h => h(App),

}).$mount('#app');

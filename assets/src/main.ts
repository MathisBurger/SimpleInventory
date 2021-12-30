import Vue from 'vue'
import App from './App.vue'
import vuetify from "./plugins/vuetify";
import stores from "./services/stores";
import VueRouter from "vue-router";
import {getRoutesAsRouteConfig} from "./Routes";
import 'vuetify/dist/vuetify.min.css';

const router = new VueRouter({
    routes: getRoutesAsRouteConfig()
})

new Vue({
    vuetify,
    router,
    data: {
        sharedState: stores.state
    },
    render: h => h(App),

}).$mount('#app');

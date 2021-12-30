import Vue from 'vue'
import App from './App.vue'
import vuetify from "./plugins/vuetify";
import stores from "./services/stores";
import VueRouter from "vue-router";
import 'vuetify/dist/vuetify.min.css';
import routes from "./Routes";

const router = new VueRouter({
    routes: routes,
    mode: 'history'
})

new Vue({
    vuetify,
    router,
    data: {
        sharedState: stores.state
    },
    render: h => h(App),

}).$mount('#app');

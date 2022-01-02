import Login from './pages/Login.vue';
import Home from './pages/Home.vue';
import {RouteConfig} from "vue-router";

/**
 * All routes that are available in the Vue frontend.
 */
const routes: RouteConfig[] = [
    {
        path: '/',
        redirect: '/dashboard'
    },
    {
        path: '/dashboard',
        component: Home
    },
    {
        path: '/login',
        component: Login
    }
];


export default routes;
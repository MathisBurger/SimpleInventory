import Login from './pages/Login.vue';
import Home from './pages/Home.vue';
import {RouteConfig} from "vue-router";
import {PermissionLevels} from "./permissions";
import UserManagement from "./pages/UserManagement.vue";

export type ExpandedRoute =  {
    permissions: PermissionLevels[];
} & RouteConfig;

/**
 * All routes that are available in the Vue frontend.
 */
const routes: ExpandedRoute[] = [
    {
        path: '/',
        redirect: '/dashboard',
        permissions: [PermissionLevels.ROLE_USER]
    },
    {
        path: '/dashboard',
        component: Home,
        permissions: [PermissionLevels.ROLE_USER]
    },
    {
        path: '/login',
        component: Login,
        permissions: [PermissionLevels.ALL]
    },
    {
        path: '/user-management',
        component: UserManagement,
        permissions: [PermissionLevels.ROLE_ADMIN]
    }
];

/**
 * Parses all routes to routes that are readable for the vue router
 */
export const getRoutes = (): RouteConfig[] => {
    let newRoutes: RouteConfig[] = [];
    routes.forEach((route) => newRoutes.push(route as RouteConfig));
    return newRoutes;
}


export default routes;
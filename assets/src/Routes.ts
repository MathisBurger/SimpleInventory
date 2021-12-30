import Vue from "vue";
import {PermissionLevels} from "./permissions";
import Login from './pages/Login.vue';
import {RouteConfig} from "vue-router";

interface RouteType extends Pick<RouteConfig, 'path' | 'props'> {
    /**
     * The page that corresponds to the given path.
     */
    component: Vue.Component;
    /**
     * The permissions that are required to access the route.
     */
    permissions: PermissionLevels[];
}

/**
 * All routes that are available in the Vue frontend.
 */
const routes: RouteType[] = [
    {
        path: '/',
        component: Login,
        permissions: [PermissionLevels.ROLE_USER]
    }
];

/**
 * Parses all routes to a type that the vue router accepts.
 *
 * @return RouteConfig[] All routes of the vue app
 */
export const getRoutesAsRouteConfig = (): RouteConfig[] => {
    return routes.map((route) => {
        return route as RouteConfig;
    });
}

export default routes;
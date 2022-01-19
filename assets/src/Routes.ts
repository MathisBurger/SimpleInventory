import Login from './pages/Login.vue';
import Home from './pages/Home.vue';
import {RouteConfig} from "vue-router";
import {PermissionLevels} from "./permissions";
import UserManagement from "./pages/UserManagement.vue";
import PermissionGroups from "./pages/PermissionGroups.vue";
import TablesList from "./pages/TablesList.vue";
import TableView from "./pages/TableView.vue";
import Glossary from "./pages/Glossary.vue";
import Updates from "./pages/Updates.vue";
import ServerInformation from "./pages/ServerInformation.vue";

/**
 * The extended route type. 
 */
export type ExpandedRoute =  {
    /**
     * Indicates what type of auth the user can have.
     */
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
    },
    {
        path: '/permission-groups',
        component: PermissionGroups,
        permissions: [PermissionLevels.ROLE_ADMIN]
    },
    {
        path: '/tables',
        component: TablesList,
        permissions: [PermissionLevels.ROLE_USER]
    },
    {
        path: '/table-view',
        component: TableView,
        permissions: [PermissionLevels.ROLE_USER]
    },
    {
        path: '/glossary',
        component: Glossary,
        permissions: [PermissionLevels.ROLE_USER]
    },
    {
        path: '/updates',
        component: Updates,
        permissions: [PermissionLevels.ROLE_USER]
    },
    {
        path: '/information',
        component: ServerInformation,
        permissions: [PermissionLevels.ROLE_USER]
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
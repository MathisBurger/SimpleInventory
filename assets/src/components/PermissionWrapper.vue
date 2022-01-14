<template>
  <router-view></router-view>
</template>

<script lang="ts">
import Vue from "vue";
import routes, {ExpandedRoute} from "../Routes";
import {PermissionLevels} from "../permissions";
import {User} from "../../typings/User";
import APIService from "../services/APIService";
import {StorageService} from "../services/storageService";

export default Vue.extend({
  name: "PermissionWrapper",

  data() {
    return {
      /**
       * The route that is currently active in the browser
       */
      route: this.$router.currentRoute.path,
      /**
       * All routes that the user can access
       */
      rootRoutes: routes,
      /**
       * The service that is used for communiciation with the REST-API
       */
      apiService: new APIService(),
      /**
       * The service that is used for handling localStorage requests and writing data
       * with more type safety.
       */
      storage: new StorageService(),
    }
  },
  methods: {
    /**
     * Fetches the current route.
     * It is the extended route within the permissions 
     * that are required to access the page.
     * 
     * @returns ExpandedRoute|null The route 
     */
    getCurrentRoute(): ExpandedRoute|null {
      let route = null;
      this.rootRoutes.forEach((r) => {
        if (r.path === this.route) {
          route = r;
        }
      });
      return route;
    },
    /**
     * Checks if the user is allowed to access this route.
     * It uses a list of all routes that are registered in the system.
     * Therefore, the workload grows with each route that has been registered.
     * 
     * @param user The user that wants to access the route
     * @returns boolean if the user can accss the requested route
     */
    checkPermission(user: User): boolean {
      const route = this.getCurrentRoute();
      if (route === null) {
        return false;
      }
      if (route.permissions.indexOf(PermissionLevels.ALL) > -1) {
        return true;
      }
      let matches = 0;
      route.permissions.forEach((perm) => {
        user.roles.forEach((role) => {
          if (perm === role) {
            matches += 1;
          }
        });
      });
      return matches > 0;
    }
  },
  /**
   * Checks the user permission on mount
   */
  async mounted() {
    const usr = await this.apiService.checkLogin();
    this.storage.setActiveUser(usr);
    if (!this.checkPermission(usr)) {
      await this.$router.push('/login');
    }
  }
});
</script>

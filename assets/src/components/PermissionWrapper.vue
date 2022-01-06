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
      route: this.$router.currentRoute.path,
      rootRoutes: routes,
      apiService: new APIService(),
      storage: new StorageService(),
    }
  },
  methods: {
    /**
     * Fetches the current route
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
     * Checks if the user is allowed to access this route
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

<style scoped>

</style>
<template>
  <v-app>
    <Header />
    <PermissionWrapper />
    <notifications group="main" position="bottom left" />
  </v-app>
</template>

<script>
import Header from "./components/Header";
import Navbar from "./components/Navbar";
import APIService from "./services/APIService";
import PermissionWrapper from "./components/PermissionWrapper";
export default {
  name: 'App',
  components: {PermissionWrapper, Navbar, Header},
  data() {
    return {
      /**
       * The service that handles communication with the REST-API
       */
      apiService: new APIService()
    }
  },
  methods: {
    /**
     * Checks if the user is logged in.
     * Otherwise, it redirects the user to the login page
     */
    async checkLogin() {
      try {
        await this.apiService.checkLogin();
      } catch (e) {
        await this.$router.push('/login');
      }
    }
  },
  async mounted() {
   await this.checkLogin();
  }
}
</script>
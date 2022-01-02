<template>
  <v-app>
    <Header />
    <Navbar v-if="stores.state.activeUser !== null" />
    <PermissionWrapper />
    <notifications group="main" position="bottom left" />
  </v-app>
</template>

<script>
import Header from "./components/Header";
import Navbar from "./components/Navbar";
import stores from "./services/stores";
import APIService from "./services/APIService";
import PermissionWrapper from "./components/PermissionWrapper";
export default {
  name: 'App',
  components: {PermissionWrapper, Navbar, Header},
  data() {
    return {
      stores: stores,
      apiService: new APIService()
    }
  },
  methods: {
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
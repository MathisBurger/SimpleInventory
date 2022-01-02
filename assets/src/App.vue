<template>
  <v-app>
    <Header />
    <Navbar v-if="stores.state.activeUser !== null" />
    <router-view></router-view>
    <notifications group="main" position="bottom left" />
  </v-app>
</template>

<script>
import Header from "./components/Header";
import Navbar from "./components/Navbar";
import stores from "./services/stores";
export default {
  name: 'App',
  components: {Navbar, Header},
  data() {
    return {
      stores: stores
    }
  }
}
</script>
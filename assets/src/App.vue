<template>
  <div>
    <Header />
    <Navbar />
    <router-view></router-view>
  </div>
</template>

<script>
import Header from "./components/Header";
import Navbar from "./components/Navbar";
export default {
  name: 'App',
  components: {Navbar, Header},
}
</script>
<template>
  <v-card
      min-height="90vh"
      width="256"
      style="margin-top: 10px;"
      class="mx-auto navbar-left"
      tile
      outlined
      rounded
      raised
  >
    <v-navigation-drawer>
      <v-list
          dense
          nav
      >
        <v-list-item
            v-for="item in items"
            :key="item.title"
            :href="item.redirect"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title style="font-size: 1.3em; font-family: 'Roboto', sans-serif">{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </v-card>
</template>

<script>
export default {
  name: "Navbar",
  data () {
    return {
      items: [
        {title: 'Dashboard', icon: 'mdi-home', redirect: '/dashboard'}
      ]
    }
  },
}
</script>

<style scoped>
  .navbar-left {
    position: absolute;
    left: 5px;
    top: 5vh;
  }
</style>
<template>
  <v-card
    color="grey lighten-4"
    flat
    tile
    >
    <v-toolbar dense>
      <v-toolbar-title tag="h1">SimpleInventory</v-toolbar-title>
      <v-spacer />
      <NavbarUserActions />
    </v-toolbar>
  </v-card>
</template>

<script>
import NavbarUserActions from './NavbarUserActions.vue'
export default {
  components: { NavbarUserActions },
  name: "Header"
}
</script>

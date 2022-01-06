<template>
  <v-container fluid>
    <v-row dense align="flex-start" no-gutters>
      <v-col md="2">
        <Navbar />
      </v-col>
      <v-col md="9">
        <v-card raised outlined>
          <v-card-title>{{title}}</v-card-title>
          <slot />
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Navbar from "./Navbar.vue";

export default Vue.extend({
  name: "PageLayout",
  props: {
    title: String,
  },
  components: {Navbar},
});
</script>

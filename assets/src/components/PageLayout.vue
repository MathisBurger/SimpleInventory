<template>
  <v-container fluid>
    <v-row dense align="flex-start">
      <v-col md="2">
        <Navbar />
      </v-col>
      <v-col md="10">
        <v-card raised outlined>
          <v-card-title>{{title}}</v-card-title>
          <div class="bottom-margin">
            <slot />
          </div>
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
    /**
     * A text that is displayed on top of main content card.
     * NOTE: You may use it as a title.
     */
    title: String,
  },
  components: {Navbar},
});
</script>

<style scoped>
  .bottom-margin {
    margin-bottom: 20px;
  }
</style>
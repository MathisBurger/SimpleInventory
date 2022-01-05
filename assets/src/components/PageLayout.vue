<template>
  <v-card raised outlined width="1300" class="center">
    <v-card-title>{{title}}</v-card-title>
    <slot />
  </v-card>
</template>

<script lang="ts">
import Vue from "vue";

export default Vue.extend({
  name: "PageLayout",
  props: {
    title: String
  }
});
</script>

<style scoped>
.center {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
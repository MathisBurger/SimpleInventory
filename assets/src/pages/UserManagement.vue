<template>
  <PageLayout title="User management">

  </PageLayout>
</template>

<script lang="ts">
import Vue from "vue";
import PageLayout from "../components/PageLayout.vue";
import APIService from "../services/APIService";

export default Vue.extend({
  name: "UserManagement",
  components: {PageLayout},
  data() {
    return {
      apiService: new APIService(),
    }
  },
  async mounted() {
    console.log(await this.apiService.getAllPermissionGroups());
  }
});
</script>

<style scoped>

</style>
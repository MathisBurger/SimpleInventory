<template>
    <PageLayout title="Updates">
       <p>
          The current version is still the latest version of SimpleInventory!
          <br><br>
          There wont be any updates in the future, because this project is finished.<br>
          Furthermore, there wont be any security patches in the future. So please only use this<br>
          application in private non public networks, so it cant be exploited.
       </p>
    </PageLayout>
</template>

<script lang="ts">
import Vue from 'vue';
import PageLayout from "../components/PageLayout.vue";

export default Vue.extend({
   name: "Updates",
   components: {PageLayout}
});
</script>
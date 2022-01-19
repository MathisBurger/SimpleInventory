<template>
    <PageLayout title="Information">
        <ServerInformationComponent />
    </PageLayout>
</template>


<script lang="ts">
import Vue from 'vue';
import PageLayout from '../components/PageLayout.vue';
import ServerInformationComponent from '../components/ServerInformationComponent.vue';

export default Vue.extend({
  components: { PageLayout, ServerInformationComponent },
   name: "ServerInformation" 
});
</script>
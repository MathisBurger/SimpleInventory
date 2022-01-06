<template>
    <PageLayout title="Permission-Groups">
        <v-data-table
        :headers="headers"
        :items="groups"
        class="elevation-1"
        show-select
        calculate-widths
      >
      <template v-slot:item.groupColor="{item}">
          <v-chip
            :color="item.groupColor"
            dark
          >Color</v-chip>
      </template>
      <template v-slot:item.tables="{item}">
          {{item.tables.length}}
      </template>
      <template v-slot:top>
          <v-btn
            color="primary"
            dark
            class="ml-5"
          >
              <v-icon left>mdi-plus</v-icon>
              new
          </v-btn>
          <v-btn
            color="error"
            dark
          >
              <v-icon left>mdi-minus</v-icon>
              delete
          </v-btn>
      </template>
      </v-data-table>
    </PageLayout>
</template>


<script lang="ts">
import { PermissionGroup } from 'assets/typings/PermissionGroup';
import Vue from 'vue';
import PageLayout from '../components/PageLayout.vue';
import APIService from '../services/APIService';

export default Vue.extend({
  components: { PageLayout },
   name: 'PermissionGroups',
   data() {
       return {
           groups: [] as Array<PermissionGroup>,
           apiService: new APIService(),
           headers: [
               {text: 'ID', value: 'id'},
               {text: 'Name', value: 'name'},
               {text: 'Color', value: 'groupColor'},
               {text: 'Tables', value: 'tables'}
           ]
       };
   },
   async mounted() {
       this.groups = (await this.apiService.getAllPermissionGroups()).groups;
   }
});
</script>
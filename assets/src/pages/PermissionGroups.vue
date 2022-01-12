<template>
    <PageLayout title="Permission-Groups">
        <v-data-table
        :headers="headers"
        :items="groups"
        class="elevation-1"
        show-select
        calculate-widths
        v-model="selectedGroups"
      >
      <template v-slot:item.groupColor="{item}">
          <v-chip
            :color="item.groupColor"
            dark
          >Color</v-chip>
      </template>
      <template v-slot:top>
          <v-btn
            color="primary"
            dark
            class="ml-5"
            @click="addDialogOpen = true"
          >
              <v-icon left>mdi-plus</v-icon>
              new
          </v-btn>
          <v-btn
            color="error"
            dark
            @click="deleteSelectedPermissionGroups"
          >
              <v-icon left>mdi-minus</v-icon>
              delete
          </v-btn>
      </template>
      </v-data-table>
      <AddPermissionGroupDialog 
        :open="addDialogOpen"
        :closeDialog="() => {addDialogOpen = false}"
        :addGroupToList="addGroupToList"
      />
    </PageLayout>
</template>


<script lang="ts">
import { PermissionGroup } from 'assets/typings/PermissionGroup';
import Vue from 'vue';
import AddPermissionGroupDialog from '../components/dialog/permissions/AddPermissionGroupDialog.vue';
import PageLayout from '../components/PageLayout.vue';
import APIService from '../services/APIService';

export default Vue.extend({
  components: { PageLayout, AddPermissionGroupDialog },
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
           ],
           addDialogOpen: false,
           selectedGroups: [] as Array<PermissionGroup>
       };
   },
   methods: {
       addGroupToList(group: PermissionGroup) {
           this.groups.push(group);
       },
       async deleteSelectedPermissionGroups() {
           for (const group of this.selectedGroups) {
               const resp = await this.apiService.deletePermissionGroup(group.id);
               this.$notify({
                    group: 'main',
                    title: 'Deletion',
                    text: resp.message,
                    type: 'success',
                    duration: 1000,
                });
           }
           this.groups = (await this.apiService.getAllPermissionGroups()).groups;
       }
   },
   async mounted() {
       this.groups = (await this.apiService.getAllPermissionGroups()).groups;
   }
});
</script>
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
      <template v-slot:item.actions="{item}">
            <v-btn
                icon
                @click="() => {
                    editableObject = item;
                    updateDialogOpen = true;
                }"
            >
            <v-icon left>mdi-edit</v-icon>
            </v-btn>
        </template>
        <template v-slot:item.tables="{item}">
            {{item.tables.length}}
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
      <UpdatePermissionGroupDialog 
        :open="updateDialogOpen"
        :closeDialog="() => {updateDialogOpen = false}"
        :userInput="editableObject"
      />
    </PageLayout>
</template>


<script lang="ts">
import { PermissionGroup } from 'assets/typings/PermissionGroup';
import Vue from 'vue';
import AddPermissionGroupDialog from '../components/dialog/permissions/AddPermissionGroupDialog.vue';
import UpdatePermissionGroupDialog from '../components/dialog/permissions/UpdatePermissionGroupDialog.vue';
import PageLayout from '../components/PageLayout.vue';
import APIService from '../services/APIService';

export default Vue.extend({
  components: { PageLayout, AddPermissionGroupDialog, UpdatePermissionGroupDialog },
   name: 'PermissionGroups',
   data() {
       return {
           /**
            * All groups that should be displayed in the grid
            */
           groups: [] as Array<PermissionGroup>,
           /**
            * The service that is used for communication with the REST-API
            */
           apiService: new APIService(),
           /**
            * All headers of the data grid
            */
           headers: [
               {text: 'ID', value: 'id'},
               {text: 'Actions', value: 'actions'},
               {text: 'Name', value: 'name'},
               {text: 'Color', value: 'groupColor'},
               {text: 'Tables', value: 'tables'}
           ],
           /**
            * Indicates whether the dialog for adding new users is opened
            */
           addDialogOpen: false,
           /**
            * All groups that are selected in the datagrid
            */
           selectedGroups: [] as Array<PermissionGroup>,
           /**
            * Indicates whether the dialog to update groups is opened.
            */
           updateDialogOpen: false,
           /**
            * The group that should be edited
            */
           editableObject: {} as any
       };
   },
   methods: {
       /**
        * Adds a new group to the list view
        */
       addGroupToList(group: PermissionGroup) {
           this.groups.push(group);
       },
       /**
        * Deletes all selected groups from the server.
        */
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
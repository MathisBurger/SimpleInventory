<template>
  <PageLayout title="User management">
      <v-data-table
        :headers="tableHeaders"
        :items="users"
        class="elevation-1"
        show-select
        calculate-widths
        v-model="selected"
      >
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
      <template v-slot:item.permissionGroups="{item}">
        <v-chip
            v-for="permission in item.permissionGroups"
          :color="permission.groupColor"
          dark
        >
        {{permission.name}}
        </v-chip>
      </template>
      <template v-slot:item.roles="{item}">
        <v-chip
            :color="getRoleColor(role)"
            dark
            v-for="role in item.roles"
        >
          {{role.replaceAll('ROLE_', '')}}
        </v-chip>
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
            @click="deleteUser"
          >
            <v-icon left>mdi-minus</v-icon>
            delete
          </v-btn>
        </template>
      </v-data-table>
    <AddUserDialog 
     :open="addDialogOpen"
     :close-dialog="() => {addDialogOpen = false}"
     :addUserToList="addUser"
    />
    <UpdateUserDialog 
      :open="updateDialogOpen"
      :closeDialog="() => {updateDialogOpen = false}"
      :userInput="editableObject"
    />
  </PageLayout>
</template>

<script lang="ts">
import Vue from "vue";
import PageLayout from "../components/PageLayout.vue";
import APIService from "../services/APIService";
import {User} from "../../typings/User";
import {PermissionLevels} from "../permissions";
import AddUserDialog from "../components/dialog/user/AddUserDialog.vue";
import UpdateUserDialog from "../components/dialog/user/UpdateUserDialog.vue";

export default Vue.extend({
  name: "UserManagement",
  components: {AddUserDialog, PageLayout, UpdateUserDialog},
  data() {
    return {
      /**
       * The service that is used for communication with the REST-API
       */
      apiService: new APIService(),
      /**
       * All users that should be displayed in the list view
       */
      users: [] as Array<User>,
      /**
       * All headers of the table
       */
      tableHeaders: [
        {text: 'ID', value: 'id', sortable: true},
        {text: 'Actions', value: 'actions'},
        {text: 'Username', value: 'userIdentifier'},
        {text: 'Permission-groups', value: 'permissionGroups'},
        {text: 'Roles', value: 'roles'},
      ],
      /**
       * Indicates whether the dialog for adding new users is currently open
       */
      addDialogOpen: false,
      /**
       * All selected users in the list view
       */
      selected: [] as Array<User>,
      /**
       * The user that can be updated
       */
      editableObject: {} as any,
      /**
       * Indicates whether the dialog for updating users is currently open
       */
      updateDialogOpen: false
    }
  },
  methods: {
    /**
     * Gets the color for the user role batch
     * 
     * @param role The role thats color should be fetched
     * @return string The color string 
     */
    getRoleColor(role: PermissionLevels): string {
      switch (role) {
        case PermissionLevels.ROLE_USER:
          return '#BEBEBE';
        case PermissionLevels.ROLE_ADMIN:
          return '#12A724';
        case PermissionLevels.ROLE_MANAGER:
          return 'rgb(255, 0, 0)'  
        default:
          return '#fff';
      }
    },
    /**
     * The user that should be added to the list view
     * 
     * @param user The user that should be added to the list view
     */
    addUser(user: User) {
      this.users.push(user);
    },
    /**
     * Deletes all selected users from the server.
     */
    async deleteUser() {
      for (const user of this.selected) {
        const resp = await this.apiService.deleteUser(user.id ?? 0);
        this.$notify({
            group: 'main',
            title: 'Deletion',
            text: resp.message,
            type: resp.success ? 'success' : 'error',
            duration: 1000,
        });
      }
      this.users = (await this.apiService.getAllUsers()).users;
    }
  },
  async mounted() {
    this.users = (await this.apiService.getAllUsers()).users;
  }
});
</script>

<style scoped>

</style>
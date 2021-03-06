<template>
  <v-dialog
    v-model="open"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Create user</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
              <v-text-field
                  label="Username"
                  required
                  v-model="username"
              ></v-text-field>
            </v-col>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
              <v-text-field
                  label="Password"
                  hint="Please choose an safe password"
                  type="password"
                  v-model="password"
              ></v-text-field>
            </v-col>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
              <v-select
                  :items="getPermissionGroupItems()"
                  label="Permission-Groups"
                  required
                  multiple
                  v-model="groups"
              ></v-select>
              <v-select
                  :items="roles"
                  label="Roles"
                  required
                  multiple
                  v-model="selectedRoles"
              ></v-select>
            </v-col>
          </v-row>
        </v-container>
        <small>*indicates required field</small>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
            color="blue darken-1"
            text
            @click="closeDialog"
        >
          Close
        </v-btn>
        <v-btn
            color="blue darken-1"
            text
            @click="() => {
              addUser();
              closeDialog();
            }"
        >
          Create
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { PermissionLevels } from "../../../permissions";
import Vue from "vue";
import {PermissionGroup} from "../../../../typings/PermissionGroup";
import APIService from "../../../services/APIService";

export default Vue.extend({
  name: "AddUserDialog",
  props: {
    /**
     * If the dialog is currently opened
     */
    open: Boolean,
    /**
     * Is used for closing the current dialog
     */
    closeDialog: Function,
    /**
     * Adds a new user to the user list view
     */
    addUserToList: Function,
  },
  data() {
    return {
      /**
       * All permission groups in the system
       */
      permissionGroups: [] as Array<PermissionGroup>,
      /**
       * The service used for communication with the REST-API
       */
      apiService: new APIService(),
      /**
       * The new username of the user
       */
      username: '',
      /**
       * The new password of the user
       */
      password: '',
      /**
       * All groups that should be added to the user
       */
      groups: [] as Array<number>,
      /**
       * All roles that should be added to the user
       */
      selectedRoles: [] as Array<PermissionLevels>,
      /**
       * All roles that can be added to the user
       */
      roles: [
        PermissionLevels.ROLE_USER,
        PermissionLevels.ROLE_ADMIN,
        PermissionLevels.ROLE_MANAGER
      ]
    };
  },
  methods: {
    /**
     * Formats all permission groups in a shape that is readable
     * for a muliselect form field
     */
    getPermissionGroupItems(): any[] {
      return this.permissionGroups.map((group) => ({
        text: group.name,
        value: group.id
      }));
    },
    /**
     * Adds a new user to the inventory system.
     */
    async addUser() {
      if (this.username && this.password) {
        const resp = await this.apiService.createUser(
            this.username,
            this.password,
            this.groups,
            this.selectedRoles
        );
        if (resp.user) {
          this.addUserToList(resp.user);
          this.$notify({
            text: resp.message,
            type: 'success',
            group: 'main',
            title: 'Creation'
          });
        } else {
          this.$notify({
            text: resp.message,
            type: 'success',
            group: 'main',
            title: 'Creation'
          });
        }

      } else {
        this.$notify({
          text: 'Please fill out all fields',
          type: 'error',
          group: 'main',
          title: 'Error'
        });
      }
    }
  },
  async mounted() {
    this.permissionGroups = (await this.apiService.getAllPermissionGroups()).groups;
  }
});
</script>

<style scoped>

</style>
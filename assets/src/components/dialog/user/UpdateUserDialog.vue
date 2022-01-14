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
                  v-model="userInput.userIdentifier"
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
                  v-model="permGroups"
              ></v-select>
              <v-select
                  :items="roles"
                  label="Roles"
                  required
                  multiple
                  v-model="userInput.roles"
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
              updateUser();
              closeDialog();
            }"
        >
          Update
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { PermissionLevels } from '../../../permissions';
import APIService from '../../../services/APIService';
import { PermissionGroup } from 'assets/typings/PermissionGroup';
import Vue from 'vue';

export default Vue.extend({
 name: "UpdateUserDialog",
  props: {
    open: Boolean,
    closeDialog: Function,
    userInput: Object
  },
  data() {
    return {
      permissionGroups: [] as Array<PermissionGroup>,
      apiService: new APIService(),
      roles: [
        PermissionLevels.ROLE_USER,
        PermissionLevels.ROLE_ADMIN,
        PermissionLevels.ROLE_MANAGER
      ],
      permGroups: [] as Array<number>
    };
  },
  methods: {
    getPermissionGroupItems(): any[] {
      return this.permissionGroups.map((group) => ({
        text: group.name,
        value: group.id
      }));
    },
    async updateUser() {
      const resp = await this.apiService.updateUser(
          this.userInput.id,
          this.userInput.userIdentifier,
          this.permGroups,
          this.userInput.roles
      );
      this.$notify({
        text: resp.message,
        type: resp.user ? 'success' :'error',
        group: 'main',
        title: 'Error'
      });
    }
  },
  async mounted() {
    this.permissionGroups = (await this.apiService.getAllPermissionGroups()).groups;
  },
  updated() {
      if (this.userInput.permissionGroups && this.permGroups.length === 0) {
        this.permGroups = this.userInput?.permissionGroups?.map((group: any) => group.id);
    }
  }
});
</script>
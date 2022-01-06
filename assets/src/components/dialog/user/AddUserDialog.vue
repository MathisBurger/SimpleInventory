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
import Vue from "vue";
import {PermissionGroup} from "../../../../typings/PermissionGroup";
import APIService from "../../../services/APIService";

export default Vue.extend({
  name: "AddUserDialog",
  props: {
    open: Boolean,
    closeDialog: Function,
    addUserToList: Function,
  },
  data() {
    return {
      permissionGroups: [] as Array<PermissionGroup>,
      apiService: new APIService(),
      username: '',
      password: '',
      groups: [] as Array<any>
    };
  },
  methods: {
    getPermissionGroupItems(): any[] {
      return this.permissionGroups.map((group) => ({
        text: group.name,
        value: group.id
      }));
    },
    async addUser() {
      if (this.username && this.password) {
        const resp = await this.apiService.createUser(
            this.username,
            this.password,
            this.groups
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
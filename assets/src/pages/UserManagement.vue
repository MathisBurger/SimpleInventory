<template>
  <PageLayout title="User management">
      <v-data-table
        :headers="tableHeaders"
        :items="users"
        class="elevation-1"
        show-select
      >
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
      </v-data-table>
  </PageLayout>
</template>

<script lang="ts">
import Vue from "vue";
import PageLayout from "../components/PageLayout.vue";
import APIService from "../services/APIService";
import {User} from "../../typings/User";
import {PermissionLevels} from "../permissions";

export default Vue.extend({
  name: "UserManagement",
  components: {PageLayout},
  data() {
    return {
      apiService: new APIService(),
      users: [] as Array<User>,
      tableHeaders: [
        {text: 'ID', value: 'id', sortable: true},
        {text: 'Username', value: 'userIdentifier'},
        {text: 'Permission-groups', value: 'permissionGroups'},
        {text: 'Roles', value: 'roles'},
      ]
    }
  },
  methods: {
    getRoleColor(role: PermissionLevels): string {
      switch (role) {
        case PermissionLevels.ROLE_USER:
          return '#BEBEBE';
        case PermissionLevels.ROLE_ADMIN:
          return '#12A724';
        default:
          return '#fff';
      }
    }
  },
  async mounted() {
    this.users = (await this.apiService.getAllUsers()).users;
  }
});
</script>

<style scoped>

</style>
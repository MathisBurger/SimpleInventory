<template>
  <v-card
      min-height="90vh"
      width="256"
      style="margin-top: 10px;"
      class="mx-auto"
      tile
      outlined
      rounded
      raised
  >
    <v-navigation-drawer>
      <v-list
          dense
          nav
      >
        <v-list-item
            v-for="item in items"
            :key="item.title"
            :href="item.redirect"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title style="font-size: 1.3em; font-family: 'Roboto', sans-serif">{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </v-card>
</template>

<script lang="ts">

import {StorageService} from "../services/storageService";
import {PermissionLevels} from "../permissions";

export default {
  name: "Navbar",
  data () {
    const storage = new StorageService();
    let listItems = [
      {title: 'Dashboard', icon: 'mdi-home', redirect: '/dashboard'},
      {title: 'Tables', icon: 'mdi-table', redirect: '/tables'},
    ];

    if ((storage.getActiveUser()?.roles ?? []).indexOf(PermissionLevels.ROLE_ADMIN) > -1) {
      listItems.push(
        {title: 'Users', icon: 'mdi-account', redirect: '/user-management'},
        {title: 'Groups', icon: 'mdi-account-box-multiple', redirect: '/permission-groups'}
        );
    }
    return {
      items: listItems
    }
  },
}
</script>

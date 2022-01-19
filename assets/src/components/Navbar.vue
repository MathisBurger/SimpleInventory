<template>
  <v-card
      min-height="90vh"
      width="256"
      class="mx-auto"
      tile
      outlined
      rounded
      raised
  >
    <v-navigation-drawer>
      <NavbarList :items="items" />
    </v-navigation-drawer>
  </v-card>
</template>

<script lang="ts">

import {StorageService} from "../services/storageService";
import {PermissionLevels} from "../permissions";
import NavbarList from './NavbarList.vue';

export default {
  components: { NavbarList },
  name: "Navbar",
  data () {
    const storage = new StorageService();
    let listItems = [
      {title: 'Dashboard', icon: 'mdi-home', redirect: '/dashboard'},
      {title: 'Tables', icon: 'mdi-table', redirect: '/tables'},
      {title: 'Information', icon: 'mdi-info', redirect: '/information'}
    ];

    // If the user is an administrator he has acces to other routes.
    if ((storage.getActiveUser()?.roles ?? []).indexOf(PermissionLevels.ROLE_ADMIN) > -1) {
      listItems.push(
        {title: 'Users', icon: 'mdi-account', redirect: '/user-management'},
        {title: 'Groups', icon: 'mdi-account-box-multiple', redirect: '/permission-groups'}
        );
    }
    return {
      /** 
       * All items that are given in the sidebar nav.
      */
      items: listItems
    }
  },
}
</script>

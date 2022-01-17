<template>
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

<script lang="ts">
import Vue from 'vue';
export default Vue.extend({
    name: "ButtonView",
    props: {
        addAction: Function,
        removeAction: Function
    }
});
</script>
<template>
    <v-dialog
    v-model="open"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Update Permission-Group</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col
                cols="12"
                sm="12"
                md="12"
            >
              <v-text-field
                  label="Name"
                  required
                  v-model="userInput.name"
                  disabled
              ></v-text-field>
            </v-col>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
              <PermissionGroupUserCard 
                :permissionGroup="userInput"
              />
            </v-col>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
              <PermissionGroupTableCard 
                :permissionGroup="userInput"
              />
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
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import PermissionGroupUserCard from '../../cards/permissions/PermissionGroupUserCard.vue';
import Vue from 'vue';
import PermissionGroupTableCard from '../../cards/permissions/PermissionGroupTableCard.vue';

export default Vue.extend({
 name: "UpdateUserDialog",
 components: {PermissionGroupUserCard, PermissionGroupTableCard},
  props: {
    /** 
     * Indicates whether the dialog is opened or not 
     */
    open: Boolean,
    /**
     * Can be used for closing the dialog from inside the dialog
     */
    closeDialog: Function,
    /**
     * The input of the permissionGroup that is used for displaying group data
     */
    userInput: Object
  },
});
</script>
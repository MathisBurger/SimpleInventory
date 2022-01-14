<template>
    <v-dialog
    v-model="open"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Create Permission-Group</span>
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
                  label="Name"
                  required
                  v-model="name"
              ></v-text-field>
            </v-col>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
              <v-color-picker v-model="groupColor" />
            </v-col>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
              <v-select
                  :items="getAllTables()"
                  label="Tables"
                  required
                  multiple
                  v-model="selectedTables"
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
              addGroup();
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
import APIService from '../../../services/APIService';
import {Table} from '../../../../typings/Table';
import Vue from 'vue';

export default Vue.extend({
    name: "AddPermissionGroupDialog",
    props: {
        /**
         * If the dialog is currently opened or not
         */
        open: Boolean,
        /**
         * Is used to close the dialog from the dialog itself
         */
        closeDialog: Function,
        /**
         * Adds the group to the list of permission groups after
         * its creation.
         */
        addGroupToList: Function,
    },
    data() {
      return {
        /**
         * All tables that are currently existing in the system.
         */
        tables: [] as Array<Table>,
        /**
         * The service used for communication with the REST-API
         */
        apiService: new APIService(),
        /**
         * The name of the new permission group
         */
        name: '',
        /**
         * The color of the new permission group (HEX, RGB)
         */
        groupColor: '',
        /**
         * All tables that are selected for being added to the new permission group
         */
        selectedTables: [] as Array<number>
      };
  },
    methods: {
      /**
       * Parses all tables into a readable type for the data grid.
       * 
       * @returns any[] All tables in a readable format.
       */
     getAllTables() {
         return this.tables.map((table) => ({
            text: table.tableName,
            value: table.id
         }));
     },
     /**
      * Tries to add a new permission group into the system. 
      * After that it throws a matching notification into the 
      * user screen.
      */
     async addGroup() {
         if (this.name) {
             const resp = await this.apiService.createPermissionGroup(
               this.name,
               this.groupColor, 
               this.selectedTables
               );
              this.$notify({
                  text: resp.message,
                  type: resp.group ? 'success' :'error',
                  group: 'main',
                  title: 'Error'
              });
              if (resp.group) {
                this.addGroupToList(resp.group);
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
        this.tables = (await this.apiService.getAllTables()).tables;
    }
})
</script>
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
        open: Boolean,
        closeDialog: Function,
        addGroupToList: Function,
    },
    data() {
    return {
      tables: [] as Array<Table>,
      apiService: new APIService(),
      name: '',
      groupColor: '',
      selectedTables: [] as Array<number>
    };
  },
    methods: {
     getAllTables() {
         return this.tables.map((table) => ({
            text: table.tableName,
            value: table.id
         }));
     },
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
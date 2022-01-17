<template>
    <PageLayout title="Table list">
        <v-data-table
          :headers="headers"
          :items="tables"
          class="elevation-1"
          show-select
          calculate-widths
          v-model="selectedTables"
          @click:row="redirectTable"
        >
        <template v-slot:item.elements="{item}">
          {{item.elements.length}}
        </template>
        <template v-slot:top>
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
            @click="deleteTable"
          >
            <v-icon left>mdi-minus</v-icon>
            delete
          </v-btn>
        </template>
        </v-data-table>
        <AddTableDialog 
          :open="addDialogOpen"
          :addTableToList="addTable"
          :closeDialog="() => {addDialogOpen = false}"
        />
    </PageLayout>
</template>

<script lang="ts">
import { Table } from 'assets/typings/Table';
import Vue from 'vue';
import AddTableDialog from '../components/dialog/tables/AddTableDialog.vue';
import PageLayout from '../components/PageLayout.vue';
import APIService from '../services/APIService';

export default Vue.extend({
  components: { PageLayout, AddTableDialog },
   name: "TablesList",
   data() {
     return {
       /**
        * All headers of the data grid
        */
       headers: [
         {text: 'ID', value: 'id', sortable: true},
         {text: 'Name', value: 'tableName'},
         {text: 'Elements', value: 'elements'}
       ],
       /**
        * All tables that are displayed in the data grid
        */
       tables: [] as Array<Table>,
       /**
        * The service that is used for communicating with the REST-API
        */
       apiService: new APIService(),
       /**
        * Indicates whether the dialog to add a new table is opened
        */
       addDialogOpen: false,
       /**
        * All tables that are currently selected in the list view
        */
       selectedTables: [] as Array<Table>
     }
   },
  async mounted() {
    this.tables = (await this.apiService.getAllTables()).tables;
  },
  methods: {
    /**
     * Deletes all selected tables from the server.
     */
    async deleteTable() {
        for (const table of this.selectedTables) {
          const resp = await this.apiService.deleteTable(table.id);
          this.$notify({
                  text: resp.message,
                  type: 'success',
                  group: 'main',
                  title: 'Error'
              });
        }
        this.tables = (await this.apiService.getAllTables()).tables;
    },
    /**
     * Adds a new table to the list view.
     * 
     * @param table The new table
     */
    addTable(table: Table) {
      this.tables.push(table);
    },
    /**
     * Redirects to the detailed view of this table.
     * 
     * @param row The table that should be redirected to
     */
    async redirectTable(row: Table) {
      await this.$router.push('/table-view?tableID=' + row.id);
    }
  }
});
</script>
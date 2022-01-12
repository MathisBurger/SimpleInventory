<template>
    <PageLayout title="Table list">
        <v-data-table
          :headers="headers"
          :items="tables"
          class="elevation-1"
          show-select
          calculate-widths
        >

        </v-data-table>
    </PageLayout>
</template>

<script lang="ts">
import { Table } from 'assets/typings/Table';
import Vue from 'vue';
import PageLayout from '../components/PageLayout.vue';
import APIService from '../services/APIService';

export default Vue.extend({
  components: { PageLayout },
   name: "TablesList",
   data() {
     return {
       headers: [
         {text: 'ID', value: 'id', sortable: true},
         {text: 'Name', value: 'tableName'},
       ],
       tables: [] as Array<Table>,
       apiService: new APIService()
     }
   },
  async mounted() {
    this.tables = (await this.apiService.getAllTables()).tables;
  }
});
</script>
<template>
    <v-dialog
    v-model="open"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Create table</span>
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
              addTable();
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
import Vue from 'vue';

export default Vue.extend({
    name: "AddTableDialog",
    data() {
        return {
            apiService: new APIService(),
            name: ''
        }
    },
    props: {
        open: Boolean,
        closeDialog: Function,
        addTableToList: Function,
    },    
    methods: {
        async addTable() {
            const resp = await this.apiService.createTable(this.name);
            this.$notify({
                  text: resp.message,
                  type: resp.table ? 'success' :'error',
                  group: 'main',
                  title: 'Error'
              });
              if (resp.table) {
                  this.addTableToList(resp.table);
              }
        }
    }
})
</script>
<template>
    <v-dialog
    v-model="open"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Create table element</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col
                cols="12"
                sm="6"
                md="6"
                v-for="objectKey in objectKeys"
                v-bind:key="objectKey"
            >
              <v-text-field
                  :label="objectKey"
                  required
                  v-model="input[objectKey]"
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
              addElement();
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
    name: "AddTableElementDialog",
    data() {
        return {
            apiService: new APIService(),
            input: {} as any
        }
    },
    props: {
        open: Boolean,
        closeDialog: Function,
        addElementToList: Function,
        objectKeys: Array,
        tableID: Number
    },    
    methods: {
        async addElement() {
            const resp = await this.apiService.createTableElement(this.tableID, this.input);
             this.$notify({
                  text: resp.message,
                  type: resp.table ? 'success' :'error',
                  group: 'main',
                  title: 'Error'
              });
              if (resp.table) {
                this.addElementToList(this.input);
                this.closeDialog();
              }
        }
    }
});
</script>
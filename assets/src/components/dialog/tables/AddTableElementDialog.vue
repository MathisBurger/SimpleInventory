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
        <v-btn
            color="blue darken-1"
            text
            @click="fieldDialogOpen = true"
        >
        <v-icon left>mdi-plus</v-icon>
          Add Field
        </v-btn>
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
    <AddTableElementFieldDialog 
      :open="fieldDialogOpen"
      :addField="addField"
      :closeDialog="() => {fieldDialogOpen = false}"
    />
  </v-dialog>
</template>


<script lang="ts">
import APIService from '../../../services/APIService';
import Vue from 'vue';
import AddTableElementFieldDialog from './AddTableElementFieldDialog.vue';

export default Vue.extend({
  components: { AddTableElementFieldDialog },
    name: "AddTableElementDialog",
    data() {
        return {
            /**
             * The service that is used for communication with the server via a REST interface.
             */
            apiService: new APIService(),
            /**
             * The initial table element that should be added
             */
            input: {} as any,
            /**
             * Indicates whether the dialog to add a new field is currently opened
             */
            fieldDialogOpen: false,
        }
    },
    props: {
        /**
         * Is the dialog is currently opened
         */
        open: Boolean,
        /**
         * Is used for closing the current dialog
         */
        closeDialog: Function,
        /**
         * Is used for adding a new table element to the parent table
         */
        addElementToList: Function,
        /**
         * All keys of the table element object. Provides all names for text input fields
         */
        objectKeys: Array,
        /**
         * The ID of the parent table, the element should be added to
         */
        tableID: Number,
        /**
         * Used for updating all table elements with a new field
         */
        rearrangeRows: Function
    },    
    methods: {
        /**
         * Adds a new table element to the parent table 
         */
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
        },
        /**
         * Adds a new field to the table elements 
         * and rearranges all rows, if this is required
         */
        addField(name: string) {
          if (this.objectKeys.length > 0) {
            this.rearrangeRows(name);
          }
          this.objectKeys.push(name);
        }
    }
});
</script>
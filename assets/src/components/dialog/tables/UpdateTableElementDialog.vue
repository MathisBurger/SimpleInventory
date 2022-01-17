<template>
    <v-dialog
    v-model="open"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Update table element</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col
                cols="12"
                sm="6"
                md="6"
                v-for="field in getObjectFields()"
                v-bind:key="field"
            >
              <v-text-field
                  :label="field"
                  required
                  v-model="object[field]"
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
              updateElement();
            }"
        >
          Update
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
          /**
           * The service that is used for communication with the server.
           */
            apiService: new APIService(),
            /**
             * The input that provides the initial data for the table that should 
             * be edited.
             */
            input: {} as any,
        }
    },
    props: {
        /**
         * If the dialog is currently opened
         */
        open: Boolean,
        /**
         * The function that is used to close the dialog
         */
        closeDialog: Function,
        /**
         * The function that is used for updating an table element
         */
        updateTableElement: Function,
        /**
         * The initial table element, that should be updated
         */
        object: Object,
        /**
         * The ID of the table that contains the element that should be updated
         */
        tableID: Number,
    },    
    methods: {
      /**
       * Used for updating an table element on the server
       */
        async updateElement() {
            const resp = await this.apiService.updateTableElement(this.object.id, this.object);
             this.$notify({
                  text: resp.message,
                  type: resp.table ? 'success' :'error',
                  group: 'main',
                  title: 'Error'
              });
              if (resp.table) {
                this.updateTableElement();

                this.closeDialog();
              }
        },
        /**
         * Returns all field the object contains except the ID, because
         * the ID should not be changed
         */
        getObjectFields() {
            return Object.keys(this.object).filter(key => key !== 'id');
        }
    }
});
</script>
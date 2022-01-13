<template>
    <v-dialog
    v-model="open"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Create table element field</span>
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
                  label="Fieldname"
                  required
                  v-model="name"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-container>
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
              addField(name);
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
import Vue from 'vue';

export default Vue.extend({
    name: "AddTableElemenrFieldDialog",
    props: {
        addField: Function,
        open: Boolean,
        closeDialog: Function
    },
    data() {
        return {
            name: ''
        }
    }
})
</script>
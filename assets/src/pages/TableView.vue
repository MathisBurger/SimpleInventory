<template>
    <PageLayout title="Table view">
        <v-data-table
            :items="elements"
            :headers="headers"
            class="elevation-1"
            show-select
            calculate-widths
            :dense="elements.length > 10"
            v-model="selectedElements"
        >
        <template v-slot:item.actions="{item}">
            <v-btn
                icon
                @click="() => {
                    editableObject = item;
                    updateDialogOpen = true;
                }"
            >
            <v-icon left>mdi-edit</v-icon>
            </v-btn>
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
            @click="deleteSelectedElements"
          >
            <v-icon left>mdi-minus</v-icon>
            delete
          </v-btn>
        </template>
        </v-data-table>
        <AddTableElementDialog
            :open="addDialogOpen"
            :closeDialog="() => {addDialogOpen = false}"
            :objectKeys="getObjectKeys()"
            :addElementToList="addElementToList"
            :tableID="tableID"
            :rearrangeRows="rearrangeElements"
        />
        <UpdateTableElementDialog 
            :open="updateDialogOpen"
            :closeDialog="() => {updateDialogOpen = false}"
            :updateTableElement="updateTableElement"
            :object="editableObject"
            :tableID="tableID"
        />
    </PageLayout>
</template>

<script lang="ts">
import Vue from 'vue';
import PageLayout from "../components/PageLayout.vue";
import AddTableElementDialog from "../components/dialog/tables/AddTableElementDialog.vue";
import APIService from '../services/APIService';
import UpdateTableElementDialog from '../components/dialog/tables/UpdateTableElementDialog.vue';

export default Vue.extend({
    name: "TableView",
    components: {PageLayout, AddTableElementDialog, UpdateTableElementDialog},
    data() {
        return {
            /**
             * All elements of the table
             */
            elements: [] as Array<any>,
            /**
             * All headers of the table
             */
            headers: [] as Array<any>,
            /**
             * The service that is used for communication with the REST-API
             */
            apiService: new APIService(),
            /**
             * Indicates whether the dialog for adding new elements is opened
             */
            addDialogOpen: false,
            /**
             * Indicates whether the dialog for updating elements is opened
             */
            updateDialogOpen: false,
            /**
             * The ID of the current table
             */
            tableID: 0,
            /**
             * All selected tables from the listview
             */
            selectedElements: [] as Array<any>,
            /**
             * The table element that can be updated.
             */
            editableObject: {} as any
        }
    },
    methods: {
        /**
         * Fetches all headers from the first object element that
         * is fetched from the server.
         */
        generateHeadersFromElements() {
            if (this.elements.length > 0) {
                this.headers = [
                    {text: 'Actions', value: 'actions'},
                    ...this.getObjectKeys().map((content) => ({
                    text: content,
                    value: content
                }))
                ];
            }
        },
        /**
         * Fetches all keys of the first object of the array key
         */
        getObjectKeys(): string[] {
            if (this.elements.length > 0) {
                return Object.keys(this.elements[0]).filter((element) => element !== 'id');
            }
            return [];
        },
        /**
         * Fetches all table elements from the table
         */
        async fetchTableElements() {
            const tableID = parseInt('' + this.$route.query.tableID, 10);
            this.tableID = tableID;
            this.elements = (await this.apiService.getTable(tableID)).table?.elements?.map((element) => {
                return {id: 0+element.id, ...element.content}
            }) ?? [];
        },
        /**
         * Adds a new table element to the list view
         * 
         * @param element The new table element
         */
        addElementToList(element: any) {
            this.fetchTableElements();
            if (this.headers.length === 0) {
                this.generateHeadersFromElements();
            }
        },
        /**
         * Deletes all selected table elements from the server.
         */
        async deleteSelectedElements() {
            for (const element of this.selectedElements) {
                console.log(element);
                const resp = await this.apiService.removeTableElement(element.id);
                this.$notify({
                  text: resp.message,
                  type: resp.table ? 'success' :'error',
                  group: 'main',
                  title: 'Error'
              });
            }
            await this.fetchTableElements();
        },
        /**
         * Rearranges all elements of the current list view and table
         * 
         * @param name The name of the new field
         */
        rearrangeElements(name: string) {
            if (this.elements.length > 0) {
                const newElements = this.elements.map((element) => {
                    const copy = Object.assign({}, element);
                    copy[name] = 'empty';
                    return copy;
                });
                this.elements = newElements;
                this.generateHeadersFromElements();
            }
        },
        /**
         * Refetches all table elements
         */
        updateTableElement() {
            this.fetchTableElements();
        }
    },
    async mounted() {
        await this.fetchTableElements();
        this.generateHeadersFromElements();
    }
})
</script>
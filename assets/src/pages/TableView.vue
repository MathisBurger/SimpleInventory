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
        >

        </AddTableElementDialog>
    </PageLayout>
</template>

<script lang="ts">
import Vue from 'vue';
import PageLayout from "../components/PageLayout.vue";
import AddTableElementDialog from "../components/dialog/tables/AddTableElementDialog.vue";
import APIService from '../services/APIService';

export default Vue.extend({
    name: "TableView",
    components: {PageLayout, AddTableElementDialog},
    data() {
        return {
            elements: [] as Array<any>,
            headers: [] as Array<any>,
            apiService: new APIService(),
            addDialogOpen: false,
            tableID: 0,
            selectedElements: [] as Array<any>
        }
    },
    methods: {
        generateHeadersFromElements() {
            if (this.elements.length > 0) {
                this.headers = this.getObjectKeys().map((content) => ({
                    text: content,
                    value: content
                }));
            }
        },
        getObjectKeys(): string[] {
            if (this.elements.length > 0) {
                return Object.keys(this.elements[0]).filter((element) => element !== 'id');
            }
            return [];
        },
        async fetchTableElements() {
            const tableID = parseInt('' + this.$route.query.tableID, 10);
            this.tableID = tableID;
            this.elements = (await this.apiService.getTable(tableID)).table?.elements?.map((element) => {
                return {id: element.id, ...element.content}
            }) ?? [];
        },
        addElementToList(element: any) {
            this.elements.push(element);
            this.fetchTableElements();
        },
        async deleteSelectedElements() {
            for (const element of this.selectedElements) {
                const resp = await this.apiService.removeTableElement(element.id);
                this.$notify({
                  text: resp.message,
                  type: resp.table ? 'success' :'error',
                  group: 'main',
                  title: 'Error'
              });
            }
            await this.fetchTableElements();
        }
    },
    async mounted() {
        await this.fetchTableElements();
        this.generateHeadersFromElements();
    }
})
</script>
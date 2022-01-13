<template>
    <PageLayout title="Table view">
        <v-data-table
            :items="elements"
            :headers="headers"
            class="elevation-1"
            show-select
            calculate-widths
            :dense="elements.length > 10"
        >
        </v-data-table>
    </PageLayout>
</template>

<script lang="ts">
import Vue from 'vue';
import PageLayout from "../components/PageLayout.vue"
import APIService from '../services/APIService';

export default Vue.extend({
    name: "TableView",
    components: {PageLayout},
    data() {
        return {
            elements: [] as Array<any>,
            headers: [] as Array<any>,
            apiService: new APIService()
        }
    },
    methods: {
        generateHeadersFromElements() {
            if (this.elements.length > 0) {
                this.headers = Object.keys(this.elements[0]).map((content) => ({
                    text: content,
                    value: content
                }));
            }
        }
    },
    async mounted() {
        const tableID = parseInt('' + this.$route.query.tableID, 10);
        this.elements = (await this.apiService.getTable(tableID)).table?.elements ?? [];
        this.generateHeadersFromElements();
    }
})
</script>
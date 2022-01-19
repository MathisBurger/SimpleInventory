<template>
    <PageLayout title="Storage information">
        <v-row class="mx-auto">
            <v-col md="2">
                <ContentCard title="Elements">
                    <p
                        class="large-text"
                    >
                        {{getNumberOfElementsInTheLocalStorage()}}
                    </p>
                </ContentCard>
            </v-col>
        </v-row>
    </PageLayout>
</template>


<script lang="ts">
import Vue from 'vue';
import ContentCard from '../components/cards/ContentCard.vue';
import PageLayout from '../components/PageLayout.vue';

export default Vue.extend({
    name: "Storage",
    components: { PageLayout, ContentCard },
    methods: {
        /**
         * The number of all elements in the local storage
         */
        getNumberOfElementsInTheLocalStorage(): number {
            return localStorage.length;
        }
    }
});
</script>

<style scoped>
    .large-text {
        font-size: 3em;
    }
</style>
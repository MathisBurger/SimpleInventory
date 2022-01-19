<template>
    <v-card class="mx-auto" elevation="3">
        <v-col align="center">
            <v-row md="12" justify="center">
                <h3>{{title}}</h3>
            </v-row>
            <v-row md="12" justify="center">
                <slot />
            </v-row>
        </v-col>
    </v-card>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
   name: "ContentCard",
   props: {
       /**
        * The title of the card
        */
       title: String,
   }
});
</script>
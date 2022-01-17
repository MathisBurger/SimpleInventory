<template>
    <v-list
            dense
            nav
        >
        <v-list-item
                v-for="item in items"
                :key="item.title"
                :href="item.redirect"
            >
            <v-list-item-icon>
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
                <v-list-item-title style="font-size: 1.3em; font-family: 'Roboto', sans-serif">{{ item.title }}</v-list-item-title>
            </v-list-item-content>
            </v-list-item>
    </v-list>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
   name: "NavbarList",
   props: {
       /**
        * All items of the navbar that should be rendered
        */
       items: Array
   } 
});
</script>
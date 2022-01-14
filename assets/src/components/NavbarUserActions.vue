<template>
    <v-menu offset-y>
        <template v-slot:activator="{on, attrs}">
            <v-btn
                color="primary"
                icon
                v-bind="attrs"
                v-on="on"
            >
                <v-icon>mdi-account</v-icon>                        
            </v-btn>
        </template>
        <v-list>
            <v-list-item
                @click="() => navigateToRoute('/login')"
            >
                <v-list-item-title>Login</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>


<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
    name: "NavbarUserActions",
    methods: {
        /**
         * Navigates to the requested route.
         * 
         * @param route The route that should be navigated to. 
         */
        async navigateToRoute(route: string) {
            await this.$router.push(route);
        }
    }
});
</script>
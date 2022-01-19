<template>
    <v-container fluid>
        <v-row dense align="flex-start">
            <v-col md="4">
                <ContentCard title="Server-status">
                    <p 
                        :style="`color: ${getServerStatusColor()}`"
                        class="large-text"
                    >
                        {{getServerStatusText()}}
                    </p>
                </ContentCard>
            </v-col>
            <v-col md="4">
                <ContentCard title="Response code">
                    <p 
                        :style="`color: ${getServerStatusColor()}`"
                        class="large-text"
                    >
                        {{getServerStatusCode()}}
                    </p>
                </ContentCard>
            </v-col>
            <v-col md="4">
                <ContentCard title="Response type">
                    <p 
                        class="large-text"
                    >
                        {{getResponseType()}}
                    </p>
                </ContentCard>
            </v-col>
            <v-col md="4">
                <ContentCard title="Transfer encoding">
                    <p 
                        class="large-text"
                    >
                        {{getTransferEncoding()}}
                    </p>
                </ContentCard>
            </v-col>
            <v-col md="4">
                <ContentCard title="Ping">
                    <p 
                        :style="`color: ${getPingColor()}`"
                        class="large-text"
                    >
                        {{getPing()}}
                    </p>
                </ContentCard>
            </v-col>
        </v-row>
    </v-container>
</template>


<script lang="ts">
import Vue from 'vue';
import ContentCard from "./cards/ContentCard.vue";

export default Vue.extend({
   name: "ServerInformationComponent",
   components: { ContentCard },
   data() {
       return {
           /**
            * The response that is returned by the servre to fetch general server info
            */
           response: new Response(),
           /**
            * The time the server required to send a response.
            */
           ping: 0,
       };
   },
   methods: {
       /**
        * Returns a color based on if the status code is OK
        */
       getServerStatusColor(): string {
           return this.response.ok ? '#25AE35' : '#FF0000';
       },
       /**
        * Returns the status text of the response.
        */
       getServerStatusText(): string {
           return this.response.ok ? 'ONLINE': 'OFFLINE'
       },
       /**
        * Returns the status code that the server responded
        */
       getServerStatusCode(): string {
           return '' + this.response.status;
       },
       /**
        * The response type of the request
        */
       getResponseType(): string {
           return this.response.type;
       },
       /**
        * Returns the transfer encoding of the response
        */
       getTransferEncoding(): string {
           return '' + this.response.headers.get("Transfer-Encoding");
       },
       /**
        * Returns the ping of the request formatted as a string
        */
       getPing(): string {
           return '' + this.ping + 'ms';
       },
       /**
        * Returns the color of the ping card by the size of the ping.
        */
       getPingColor(): string {
           return this.ping < 50 ? '#25AE35' : '#FF0000';
       },
       /**
        * Loads all important data from the server
        */
       async loadData() {
            const startUnix = (new Date()).getTime();
            const resp = await fetch('/');
            const endUnix = (new Date()).getTime();
            this.response = resp;
            this.ping = endUnix - startUnix;
       }
   },
   async mounted() {
       await this.loadData();
       setInterval(this.loadData, 1000);
   },
});
</script>

<style scoped>
    .large-text {
        font-size: 3em;
    }
</style>
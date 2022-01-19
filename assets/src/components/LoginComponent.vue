<template>
    <v-card raised outlined width="256">
      <v-card-title>Login</v-card-title>
      <v-card-text>
        <v-text-field
          label="Username"
          :rules="rules"
          hide-details="auto"
          v-model="username"
        />
        <v-text-field
          label="Password"
          :rules="rules"
          hide-details="auto"
          type="password"
          v-model="password"
        />
        <v-btn
            color="primary"
            depressed
            style="margin-top: 10px"
            @click="() => login(username, password)"
        >Login</v-btn>
      </v-card-text>
  </v-card>
</template>

<script lang="ts">
import Vue from 'vue';

export default Vue.extend({
    name: "LoginComponent",
    data() {
        return {
            /**
              * Rules for the input fields
              */
              rules: [
                  (value: string) => !!value || 'Required.'
              ],
              /**
               * The username the user inserted
               */
              username: '',
              /**
               * The password, the user inserted
               */
              password: '',
        }
    },
    props: {
        /**
         * The function that is executed on login
         */
        login: Function
    }    
})
</script>
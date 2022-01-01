<template>
  <v-card raised outlined width="256" class="centered-login">
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
        <v-btn color="primary" depressed style="margin-top: 10px">Login</v-btn>
      </v-card-text>
  </v-card>
</template>

<script lang="ts">
import Vue from 'vue';
import APIService from "../services/APIService";


export default Vue.extend({
  name: "Login",
  data: () => ({
    rules: [
        (value: string) => !!value || 'Required.'
    ],
    username: '',
    password: '',
    apiService: new APIService()
  }),
  methods: {
    login: () => {
    }
  }

});
</script>

<style scoped>
.centered-login {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
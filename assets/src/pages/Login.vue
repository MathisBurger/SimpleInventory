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
        <v-btn
            color="primary"
            depressed
            style="margin-top: 10px"
            @click="login"
        >Login</v-btn>
      </v-card-text>
  </v-card>
</template>

<script lang="ts">
import Vue from 'vue';
import APIService from "../services/APIService";
import {LoginData, LoginMethods} from "../../typings/pages/Login";
import {DefaultProps} from "vue/types/options";

export default Vue.extend<LoginData, LoginMethods, DefaultProps>({
  name: "Login",
  data() {
      return {
        rules: [
            (value: string) => !!value || 'Required.'
        ],
        username: '',
        password: '',
        apiService: new APIService()
      }
  },
  methods: {
    login() {
      this.apiService.login(this.username, this.password)
          .then(() => console.log('Login successful'))
          .catch(() => console.log('Login failed'));
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
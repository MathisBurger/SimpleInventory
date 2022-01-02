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
import stores from "../services/stores";
import {User} from "../../typings/User";

export default Vue.extend<LoginData, LoginMethods, DefaultProps>({
  name: "Login",
  data() {
      return {
        rules: [
            (value: string) => !!value || 'Required.'
        ],
        username: '',
        password: '',
        apiService: new APIService(),
        stores: stores
      }
  },
  methods: {
    async login() {
      try {
        const login = await this.apiService.login(this.username, this.password);
        this.stores.setter.setActiveUser(login as User);
        await this.$router.push('/dashboard');
      } catch (e) {
          this.$notify({
            group: 'main',
            title: 'Login',
            text: 'Login failed',
            type: 'error',
            duration: 1000,
          });
      }
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
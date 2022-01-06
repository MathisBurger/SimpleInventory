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
import {User} from "../../typings/User";
import {StorageService} from "../services/storageService";

export default Vue.extend({
  name: "Login",
  data() {
      return {
        rules: [
            (value: string) => !!value || 'Required.'
        ],
        username: '',
        password: '',
        apiService: new APIService(),
        storage: new StorageService(),
      }
  },
  methods: {
    async login() {
      try {
        const login = await this.apiService.login(this.username, this.password);
        this.storage.setActiveUser(login as User);
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
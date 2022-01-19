<template>
  <div class="centered-login">
    <LoginComponent 
      :username="username"
      :password="password"
      :login="login"
    />
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import APIService from "../services/APIService";
import {User} from "../../typings/User";
import {StorageService} from "../services/storageService";
import LoginComponent from '../components/LoginComponent.vue';

export default Vue.extend({
  components: { LoginComponent },
  name: "Login",
  data() {
      return {
        /**
         * The inserted username
         */
        username: '',
        /**
         * The inserted password
         */
        password: '',
        /**
         * The service used for communication with the REST-API
         */
        apiService: new APIService(),
        /**
         * The service used for storing user specific data into the local storage
         */
        storage: new StorageService(),
      }
  },
  methods: {
    /**
     * Used for logging in a user with the provided credentials.
     */
    async login(username: string, password: string) {
      try {
        const login = await this.apiService.login(username, password);
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
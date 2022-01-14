<template>
    <v-card
        class="mx-auto"
        max-width="250"
    >
        <v-list dense>
            <v-subheader>Users</v-subheader>
                <v-list-item
                    v-for="(item, key) in permissionGroup.users"
                    :key="key"
                >
                    <v-list-item-content>
                        <v-list-item-title v-text="item.name" />
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-btn
                            color="primary"
                            icon
                            @click="() => removeUser(item.id)"
                        >
                            <v-icon left>mdi-minus</v-icon>
                        </v-btn>
                    </v-list-item-action>
                </v-list-item>
                <v-list-item>
                        <v-menu offset-y>
                            <template v-slot:activator="{on, attrs}">
                                <v-btn
                                    color="primary"
                                    text
                                    v-bind="attrs"
                                    v-on="on"
                                >
                                    <v-icon left>mdi-plus</v-icon>
                                    Add user                            
                                </v-btn>
                            </template>
                            <v-list v-if="unaddedUsers.length > 0">
                                <v-list-item
                                    v-for="(user, key) in unaddedUsers"
                                    :key="key"
                                    @click="() => addUser(user.id, user.name)"
                                >
                                    <v-list-item-title v-text="user.name" />
                                </v-list-item>
                            </v-list>
                        </v-menu>
                </v-list-item>
        </v-list>
    </v-card>
</template>

<script lang="ts">
import APIService from '../../../services/APIService';
import Vue from 'vue';
import { PermissionGroupUserType } from 'assets/typings/PermissionGroup';
import { User } from 'assets/typings/User';

export default Vue.extend({
    name: "PermissionGroupUserCard",
    data() {
        return {
            apiService: new APIService(),
            unaddedUsers: [] as Array<PermissionGroupUserType>,
        };
    },
    methods: {
        async fetchUnaddedUsers() {
            const users = (await this.apiService.getAllUsers()).users;
            const existingIDs = this.permissionGroup.users.map((u: PermissionGroupUserType) => u.id);
            const unaddeeUsers: PermissionGroupUserType[] = [];
            users.forEach((user: User) => {
                if (existingIDs.indexOf(user.id ?? -1) < 0) {
                    unaddeeUsers.push({
                        id: user.id ?? -1,
                        name: user.userIdentifier
                    });
                }
            });
            this.unaddedUsers = unaddeeUsers;
        },
        async removeUser(id: number) {
            try {
                const resp = await this.apiService.removeUserFromPermissionGroups(this.permissionGroup.id ?? -1, id);
                this.permissionGroup.users = this.permissionGroup.users.filter((e: any) => e.id !== id);
                this.$notify({
                  text: resp.message,
                  type: 'success',
                  group: 'main',
                  title: 'Permission-Group'
                });
                await this.fetchUnaddedUsers();
            } catch (e: any) {
                this.$notify({
                  text: e,
                  type: 'error',
                  group: 'main',
                  title: 'Error'
                });
            }

        },
        async addUser(id: number, name: string) {
            try {
                const resp = await this.apiService.addUserToPermissionGroup(this.permissionGroup.id ?? -1, id);
                this.permissionGroup.users.push({
                    id,
                    name,
                });
                this.$notify({
                  text: resp.message,
                  type: 'success',
                  group: 'main',
                  title: 'Permission-Group'
                });
                await this.fetchUnaddedUsers();
            } catch (e: any) {
                this.$notify({
                  text: e,
                  type: 'error',
                  group: 'main',
                  title: 'Error'
                });
            }
        }
    },
    props: {
        permissionGroup: Object
    },
    async mounted() {
        await this.fetchUnaddedUsers();
    }
})
</script>
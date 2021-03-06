<template>
    <v-card
        class="mx-auto"
        max-width="250"
    >
        <v-list dense>
            <v-subheader>Tables</v-subheader>
                <v-list-item
                    v-for="(item, key) in permissionGroup.tables"
                    :key="key"
                >
                    <v-list-item-content>
                        <v-list-item-title v-text="item.name" />
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-btn
                            color="primary"
                            icon
                            @click="() => removeTable(item.id)"
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
                                    Add table                            
                                </v-btn>
                            </template>
                            <v-list v-if="unaddedTables.length > 0">
                                <v-list-item
                                    v-for="(table, key) in unaddedTables"
                                    :key="key"
                                    @click="() => addTable(table.id, table.name)"
                                >
                                    <v-list-item-title v-text="table.name" />
                                </v-list-item>
                            </v-list>
                        </v-menu>
                </v-list-item>
        </v-list>
    </v-card>
</template>


<script lang="ts">
import APIService from '../../../services/APIService';
import { PermissionGroupTableType } from '../../../../typings/PermissionGroup';
import Vue from 'vue';
import { Table } from 'assets/typings/Table';

export default Vue.extend({
   name: "PermissionGroupTableCard",
   data() {
        return {
            /**
             * The service that handles the communication with the REST-API
             */
            apiService: new APIService(),
            /**
             * All tables that are not added to the permission group.
             * Is used for giving options for tables that can be added to the group.
             */
            unaddedTables: [] as Array<PermissionGroupTableType>,
        };
    },
    methods: {
        /**
         * Fetches all tables from the server and then filters
         * out all tables that are already included in the 
         * permission group. All tables that are not in the permission
         * group yet are set.
         */
        async fetchUnaddedTables() {
            const tables = (await this.apiService.getAllTables()).tables;
            const existingIDs = this.permissionGroup.tables.map((t: PermissionGroupTableType) => t.id);
            const unaddedTables: PermissionGroupTableType[] = [];
            tables.forEach((table: Table) => {
                if (existingIDs.indexOf(table.id ?? -1) < 0) {
                    unaddedTables.push({
                        id: table.id ?? -1,
                        name: table.tableName
                    });
                }
            });
            this.unaddedTables = unaddedTables;
        },
        /**
         * Removes an table from the current permission group.
         * It shows notifications, if the action failed or was successful.
         * Furthermore it updates the list view.
         * 
         * @param id The ID of the table that should be removed from the permission group
         */
        async removeTable(id: number) {
            try {
                const resp = await this.apiService.removeTableFromPermissionGroup(this.permissionGroup.id ?? -1, id);
                this.permissionGroup.tables = this.permissionGroup.tables.filter((e: any) => e.id !== id);
                this.$notify({
                  text: resp.message,
                  type: 'success',
                  group: 'main',
                  title: 'Permission-Group'
                });
                await this.fetchUnaddedTables();
            } catch (e: any) {
                this.$notify({
                  text: e,
                  type: 'error',
                  group: 'main',
                  title: 'Error'
                });
            }

        },
        /**
         * Adds an table from the current permission group.
         * It shows notifications, if the action failed or was successful.
         * Furthermore it updates the list view.
         * 
         * @param id The ID of the table that should be added to the permission group
         */
        async addTable(id: number, name: string) {
            try {
                const resp = await this.apiService.addTableToPermissionGroup(this.permissionGroup.id ?? -1, id);
                this.permissionGroup.tables.push({
                    id,
                    name,
                });
                this.$notify({
                  text: resp.message,
                  type: 'success',
                  group: 'main',
                  title: 'Permission-Group'
                });
                await this.fetchUnaddedTables();
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
        /**
         * The current permission group that is provided by the parent update component.
         */
        permissionGroup: Object
    },
    async mounted() {
        await this.fetchUnaddedTables();
    } 
});
</script>
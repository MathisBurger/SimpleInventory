<template>
    <PageLayout title="Glossary">
        <v-data-table 
            :headers="headers"
            :items="items"
        />
    </PageLayout>
</template>

<script lang="ts">
import Vue from 'vue';
import PageLayout from '../components/PageLayout.vue';

export default Vue.extend({
  components: { PageLayout },
  name: "Glossary",
  data() {

      return {
          /**
           * All headers of the table
           */
          headers: [
              {text: 'Title', value: 'title'},
              {text: 'Description', value: 'description'}
          ],
          /**
           * All items of the table
           */
          items: [
              /**
               * The description of a table
               */
              {
                  title: 'Table',
                  description: 'A table contains multiple elements. Each of these elements has a different shape of data. ' +
                  'Furthermore, any user can add new table elements to a table. Any logged in user has access to some tables. ' + 
                  'The administrator in general has access to all tables in the inventory system.'
              },
              /**
               * The description of a permission group
               */
              {
                  title: 'Permission group',
                  description: 'A permission groups contains multiple users and tables. All users in ' +
                  'a permission group are having access to all tables in the group. Only administrators are having ' +
                  'the permission to add and remove users from a permission group. Furthermore, only administrators are allowed ' + 
                  'to add new tables to a permission group. One more thing is that you can only add existing elements to a ' +
                  'permission group and not create a new one'
              },
              /**
               * The description of a user
               */
              {
                  title: 'User',
                  description: 'The word user is self declaring. A user can interact with the web interface and perform changes. ' +
                  'There are different types of users. There are administrators for example. Administrators are having access to ' +
                  'absolutely everything.'
              },
              /**
               * The description of the default user
               */
              {
                  title: 'Default user',
                  description: 'A default user only can view the glossary and view and edit tables. A default user is allowed to ' +
                  'update elements in table and so on'
              },
              /**
               * The description of an managing user
               */
              {
                  title: 'Managing user',
                  description: 'A manager is allowed to manage users. Furthermore, he has all permissions that the default user ' +
                  'also has. So being an manager does not bring such big improvenemts to you.'
              },
              /**
               * The description of an administrator
               */
              {
                  title: 'Administrator',
                  description: 'An admininstrator is an superuser. He has access to everything. He can manage users, ' + 
                  'permission-groups and tables. Furthermore, he is is the only one, who can create new administrators.'
              },
              /**
               * The description of the dashboard
               */
              {
                  title: 'Dashboard',
                  description: 'Like you have noticed, the dashboard is empty.'
              }
          ]
      };
  }
});
</script>
<template>
  <v-app>
    <v-navigation-drawer app>
      <v-navigation-drawer absolute permanent left>
        <template v-slot:prepend>
          <v-list-item two-line>
            <v-list-item-content>
              <v-list-item-title><b>Insider Challenge</b></v-list-item-title>
              <v-list-item-subtitle>By Mohamad Habibi</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </template>

        <v-divider></v-divider>

        <v-list dense>
          <v-list-item
            v-for="item in items"
            :key="item.title"
            @click=""
            :to="item.to"
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
    </v-navigation-drawer>

    <v-container>
      <router-view></router-view>
    </v-container>
  </v-app>
</template>

<script>
export default {
  name: "App",

  data() {
    return {
      items: [
          { title: "About Me", icon: "mdi-account", to: "/" },
          { title: "Leagues", icon: "mdi-view-headline", to: "/leagues" },
          { title: "New team", icon: "mdi-plus", to: "/team/new" },
          { title: "New league", icon: "mdi-plus", to: "/league/new" }
      ]
    };
  }
};
</script>

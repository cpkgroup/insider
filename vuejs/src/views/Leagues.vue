<template>
    <v-content>
        <h1>Leagues</h1>

        <v-simple-table fixed-header height="500px">
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">Id</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Table</th>
                </tr>
                <tr>
                    <td colspan="5" :hidden="!loading">
                        <v-progress-linear
                                :active="loading"
                                color="red"
                                :indeterminate="true"
                        ></v-progress-linear>
                    </td>
                </tr>
                </thead>
                <tbody :hidden="loading">
                <tr v-for="league of leagues" v-bind:key="league.id">
                    <td>{{ league.id }}</td>
                    <td>{{ league.name }}</td>
                    <td>

                        <router-link :to="'/league/' + league.id + '/week/1'">
                            <v-btn x-small color="primary">
                                Show Table
                            </v-btn>
                        </router-link>

                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
    </v-content>
</template>

<script>
    export default {
        name: "logs",
        data() {
            return {
                leagues: [],
                loading: true,
            };
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.loading = true;
                this.$http
                    .get("show_leagues")
                    .then(response => {
                        this.leagues = response.data.leagues;
                        this.loading = false;
                    })
                    .catch(e => {
                        console.error(e);
                    });
            }
        }
    };
</script>

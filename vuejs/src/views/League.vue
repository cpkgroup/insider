<template>
    <v-content>
        <h1>League Table</h1>

        <v-simple-table fixed-header height="300px">
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">Position</th>
                    <th class="text-left">Club</th>
                    <th class="text-left">Played</th>
                    <th class="text-left">Won</th>
                    <th class="text-left">Drawn</th>
                    <th class="text-left">Lost</th>
                    <th class="text-left">GF</th>
                    <th class="text-left">GA</th>
                    <th class="text-left">GD</th>
                    <th class="text-left">Points</th>
                    <th class="text-left">Chance</th>
                </tr>
                <tr>
                    <td colspan="11" :hidden="!loading">
                        <v-progress-linear
                                :active="loading"
                                color="red"
                                :indeterminate="true"
                        ></v-progress-linear>
                    </td>
                </tr>
                </thead>
                <tbody :hidden="loading">
                <tr v-for="team of teams" v-bind:key="team.TeamId">
                    <td>{{ teams.indexOf(team) + 1 }}</td>
                    <td>{{ team.TeamName }}</td>
                    <td>{{ team.P }}</td>
                    <td>{{ team.W }}</td>
                    <td>{{ team.D }}</td>
                    <td>{{ team.L }}</td>
                    <td>{{ team.GF }}</td>
                    <td>{{ team.GA }}</td>
                    <td>{{ team.GD }}</td>
                    <td>{{ team.PTS }}</td>
                    <td>{{ team.chance }}</td>
                    <td>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>


        <h3 :hidden="loading">{{ this.$route.params.week }}th Week Match Result</h3>

        <v-simple-table fixed-header height="100px">
            <template v-slot:default>
                <thead>
                </thead>
                <tbody :hidden="loading">
                <tr v-for="match of matches">
                    <td class="text-left">{{ match.host }}</td>
                    <td class="text-center">{{ match.goalsHost }} - {{ match.goalsGuest }}</td>
                    <td class="text-right">{{ match.gust }}</td>
                    <td>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>


        <router-link :to="'/league/' + $route.params.id + '/week/' + nextWeek">
            <v-btn x-small :hidden="loading" color="primary" @click="playMatch">
                Play
            </v-btn>
        </router-link>
        &nbsp;
        <router-link :to="'/league/' + $route.params.id + '/week/1'" :hidden="loading">
            <v-btn x-small color="primary" @click="playAllMatch">
                Play all
            </v-btn>
        </router-link>
        &nbsp;
        <router-link :hidden="loading" :to="'/league/' + $route.params.id + '/week/' + nextWeek">
            <v-btn x-small color="primary" @click="fetchData">
                Next week
            </v-btn>
        </router-link>

    </v-content>
</template>

<script>
    export default {
        name: "logs",
        data() {
            return {
                teams: [],
                matches: [],
                loading: true,
                nextWeek: 2,
            };
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.loading = true;
                this.$http
                    .get("show_week?leagueId=" + this.$route.params.id + '&week=' + this.$route.params.week)
                    .then(response => {
                        this.teams = response.data.table;
                        this.matches = response.data.matches;
                        this.loading = false;
                        this.nextWeek = parseInt(this.$route.params.week) + 1;
                        this.nextWeek = response.data.weeks > this.nextWeek ? this.nextWeek : response.data.weeks;

                    })
                    .catch(e => {
                        console.error(e);
                    });
            },
            playMatch() {
                this.loading = true;
                this.$http
                    .get("play_match?leagueId=" + this.$route.params.id + '&week=' + this.$route.params.week)
                    .then(response => {
                        this.loading = false;
                        this.nextWeek = parseInt(this.$route.params.week) + 1;
                        this.nextWeek = response.data.weeks > this.nextWeek ? this.nextWeek : response.data.weeks;
                        this.fetchData();
                    })
                    .catch(e => {
                        console.error(e);
                    });
            },
            playAllMatch() {
                this.loading = true;
                this.$http
                    .get("play_all_match?leagueId=" + this.$route.params.id)
                    .then(response => {
                        this.loading = false;
                        this.nextWeek = 2;
                        this.fetchData();
                    })
                    .catch(e => {
                        console.error(e);
                    });
            }
        }
    };
</script>

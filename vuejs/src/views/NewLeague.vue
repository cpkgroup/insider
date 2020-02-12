<template>
    <v-content>
        <h1>New League</h1>
        <v-form ref="form" v-model="valid">
            <v-text-field
                    v-model="formData.name"
                    :rules="[rules.required]"
                    label="League Name"
                    hint="String"
                    required
            ></v-text-field>

            <p>Please choose just 4 team:</p>

            <v-checkbox v-model="formData.teams[team.id]" v-for="team of teams" v-bind:key="team.id" :label="team.name" :value="team.id"></v-checkbox>

            <v-btn
                    :disabled="!valid"
                    color="primary"
                    class="mr-4"
                    @click="createPost()"
            >
                Create
                <v-icon dark right>mdi-checkbox-marked-circle</v-icon>
            </v-btn>

        </v-form>

        <v-snackbar v-model="snackbar" top="true" vertical="true">
            {{ snackbarText }}
            <v-btn color="blue" text @click="snackbar = false">
                Close
            </v-btn>
        </v-snackbar>
    </v-content>
</template>

<script>
    export default {
        name: "new",
        data() {
            return {
                teams: [],
                valid: true,
                snackbar: false,
                snackbarText: "",
                // form data here
                formData: {
                    name: "",
                    teams: [],
                },
                rules: {
                    required: value => !!value || "Required"
                }
            };
        },
        mounted: function () {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.$http
                    .get("show_teams")
                    .then(response => {

                        this.teams = response.data.teams;
                    })
                    .catch(e => {
                        console.error(e);
                    });
            },
            createPost() {

                console.log(this.formData.teams);

                this.snackbar = true;
                this.snackbarText = "Is sending...";
                this.$http
                    .post("league", {
                        name: this.formData.name,
                        teams: this.formData.teams
                    })
                    .then(response => {
                        if (response && response.status) {
                            window.setTimeout(function () {
                                this.snackbar = false;
                            }, 2000);
                            this.reset();
                            this.snackbarText = "Successfully added";
                        }
                    })
                    .catch(error => {
                        let errorMessage = "Error on creating!";
                        if (error.response) {
                            errorMessage = JSON.stringify(error.response.data.errors);
                        }
                        this.snackbar = true;
                        this.snackbarText = errorMessage;
                    });
            },
            reset() {
                this.$refs.form.reset()
            }
        }
    };
</script>
<style></style>

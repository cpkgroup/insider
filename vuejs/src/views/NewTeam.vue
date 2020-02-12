<template>
    <v-content>
        <h1>New Team</h1>
        <v-form ref="form" v-model="valid">
            <v-text-field
                    v-model="formData.name"
                    :rules="[rules.required]"
                    label="Team Name"
                    hint="String"
                    required
            ></v-text-field>

            <v-text-field
                    v-model="formData.strength"
                    :rules="[rules.required]"
                    label="Team Strength"
                    hint="number 1 to 10"
                    required
            ></v-text-field>

            <v-btn
                    :disabled="!valid"
                    color="primary"
                    class="mr-4"
                    @click="createPost()"
            >
                Send
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
                valid: true,
                snackbar: false,
                snackbarText: "",
                // form data here
                formData: {
                    name: "",
                    strength: "",
                },
                rules: {
                    required: value => !!value || "Required"
                }
            };
        },
        methods: {
            createPost() {
                this.snackbar = true;
                this.snackbarText = "Is Loading...";
                this.$http
                    .post("team", {
                        name: this.formData.name,
                        strength: this.formData.strength
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

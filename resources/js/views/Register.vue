<template>
    <div class="d-flex fill-height align-center justify-center">
        <v-container>
            <v-row>
                <v-col cols="12" md="6" offset-md="3">
                    <v-form ref="form" @submit.prevent="register()">
                        <v-card class="custom-shadow">
                            <v-card-title>Create an Account</v-card-title>
                            <v-card-text>
                                <v-text-field
                                    rounded
                                    filled
                                    label="Full Name*"
                                    v-model="fields.name.value"
                                    :rules="fields.name.rule"
                                ></v-text-field>

                                <v-text-field
                                    rounded
                                    filled
                                    label="Email*"
                                    type="email"
                                    v-model="fields.email.value"
                                    :rules="fields.email.rule"
                                ></v-text-field>
                                <v-text-field
                                    rounded
                                    filled
                                    label="Password*"
                                    type="password"
                                    v-model="fields.password.value"
                                    :rules="fields.password.rule"
                                ></v-text-field>
                                <v-text-field
                                    rounded
                                    filled
                                    label="Confirm Password*"
                                    type="password"
                                    v-model="fields.confirmPassword.value"
                                    :rules="fields.confirmPassword.rule"
                                ></v-text-field>
                                <div class="my-4 text-center">
                                    <router-link to="/login">
                                        Already have an account? Login now
                                    </router-link>
                                </div>
                            </v-card-text>
                            <v-card-text class="text-center">
                                <v-btn
                                    :loading="loading"
                                    type="submit"
                                    depressed
                                    x-large
                                    color="blue"
                                    dark
                                >
                                    Create my account
                                </v-btn>
                            </v-card-text>
                        </v-card>
                    </v-form>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import BaseClass from '@/base';

type Field = {
    [key: string]: Record<string, any>;
};

@Component
export default class LoginView extends BaseClass {
    fields: Field = {
        name: {
            value: '',
            rule: [(v: string) => v.length > 0 || 'Name is required.']
        },
        email: {
            value: '',
            rule: [(v: string) => /.*@.*/.test(v) || 'Invalid e-mail format.']
        },

        password: {
            value: '',
            rule: [(v: string) => v.length > 0 || 'Invalid password.']
        },

        confirmPassword: {
            value: '',
            rule: [
                (v: string) =>
                    this.fields.password.value === v ||
                    'Passwords do not match.'
            ]
        }
    };

    private async register() {
        this.clearAlertMessage();
        this.loading = true;
        try {
            const keys = Object.keys(this.fields);
            const formData = {};
            keys.forEach(key => {
                Object.assign(formData, {
                    [key]: this.fields[key].value
                });
            });

            const result = await this.$axios.post('/register', formData);
        } catch (error) {
            this.showAlertMessage({
                type: 'error',
                text: error.message
            });
        }

        this.loading = false;
    }
}
</script>

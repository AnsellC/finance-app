<template>
    <div class="d-flex fill-height align-center justify-center">
        <v-container>
            <v-row>
                <v-col cols="12" md="6" offset-md="3">
                    <alert-messages></alert-messages>
                    <v-form ref="form" @submit.prevent="login()">
                        <v-card class="custom-shadow">
                            <v-card-title>Login</v-card-title>
                            <v-card-text>
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
                                <div class="my-4 text-center">
                                    <router-link to="/register"
                                        >Don't have an account yet? Create
                                        one</router-link
                                    >
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
                                    Login
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

@Component
export default class LoginView extends BaseClass {
    fields = {
        email: {
            value: '',
            rule: [(v: string) => /.*@.*/.test(v) || 'Invalid e-mail format.']
        },

        password: {
            value: '',
            rule: [(v: string) => v.length > 0 || 'Invalid password.']
        }
    };

    private async login() {
        this.clearAlertMessage();
        this.loading = true;
        try {
            await this.$user.login(
                this.fields.email.value,
                this.fields.password.value
            );
            document.location.href = '/';
        } catch (error) {
            this.handleError(error);
        }

        this.loading = false;
    }
}
</script>

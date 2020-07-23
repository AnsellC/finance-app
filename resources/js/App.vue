<template>
    <v-app>
        <template v-if="$isSinglePage">
            <router-view></router-view>
        </template>
        <template v-else>
            <v-app-bar app flat>
                <v-toolbar-title class="d-flex align-center">
                    <v-icon x-large color="blue" class="mr-1"
                        >mdi-poll-box</v-icon
                    >
                    <span class="text-h6">
                        Your<span class="blue--text">Balance</span>
                    </span>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-menu offset-y open-on-hover>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="transparent"
                            depressed
                            v-on="on"
                            v-bind="attrs"
                        >
                            {{ $user.name }}
                            <v-icon class="ml-2">mdi-menu-down</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="logout()">
                            <v-list-item-title>
                                Logout
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-app-bar>
            <v-main>
                <router-view></router-view>
            </v-main>
        </template>
    </v-app>
</template>
<script lang="ts">
import { Component, Watch } from 'vue-property-decorator';
import BaseClass from '@/base';

@Component
export default class App extends BaseClass {
    @Watch('$route', { immediate: true })
    onRouteChanged() {
        this.clearAlertMessage();
    }

    private async logout() {
        await this.$user.logout();
        document.location.href = '/';
    }
}
</script>

<style lang="scss">
.theme--light.v-application {
    background: #eceff1 !important;

    .custom-shadow {
        box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.08) !important;
    }
}
</style>

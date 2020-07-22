<template>
    <v-app>
        <template v-if="$isSinglePage">
            <router-view></router-view>
        </template>
        <template v-else>
            <v-app-bar app flat>
                <v-toolbar-title>
                    Finance
                </v-toolbar-title>
                <v-spacer></v-spacer>
                User
            </v-app-bar>
            <v-main>
                <v-container>
                    <alert-messages></alert-messages>
                    <router-view></router-view>
                </v-container>
            </v-main>
        </template>
    </v-app>
</template>
<script lang="ts">
import { Component, Watch } from 'vue-property-decorator';
import BaseClass from '@/base';

@Component
export default class App extends BaseClass {
    @Watch('$route', {immediate: true})
    onRouteChanged() {
        this.clearAlertMessage();
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

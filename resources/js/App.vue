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
                    <v-alert
                        border="left"
                        v-if="alertMessage.type !== null"
                        :type="alertMessage.type"
                    >
                        {{ alertMessage.text }}
                    </v-alert>
                    <router-view></router-view>
                </v-container>
            </v-main>
        </template>
    </v-app>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import BaseClass from '@/base';

@Component
export default class App extends BaseClass {}
</script>

<style lang="scss">
.theme--light.v-application {
    background: #eceff1 !important;

    .custom-shadow {
        box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.08) !important;
    }
}
</style>

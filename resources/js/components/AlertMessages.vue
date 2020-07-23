<template>
    <v-alert
        v-if="alertMessage.type !== null"
        :type="alertMessage.type"
        prominent
    >
        <template v-if="alertMessage.errors && alertMessage.errors.length > 0">
            <h2 class="text-h6">{{ alertMessage.text }}</h2>
            <ul>
                <li v-for="(message, i) in alertMessage.errors" :key="i">
                    {{ message }}
                </li>
            </ul>
        </template>
        <template v-else>
            {{ alertMessage.text }}
        </template>
    </v-alert>
</template>
<script lang="ts">
import { Component } from 'vue-property-decorator';
import BaseClass from '@/base';

@Component
export default class App extends BaseClass {}
</script>

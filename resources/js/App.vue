<template>
    <v-app>
        <v-btn color="blue" depressed x-large class="rounded-xl"> </v-btn>
        <v-card color="blue">
            <v-card-title>test</v-card-title>
        </v-card>
    </v-app>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component
export default class App extends Vue {
    mounted() {
        console.log('hey2');
    }
}
</script>

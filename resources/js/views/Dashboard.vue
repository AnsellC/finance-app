<template>
    <div>
        <div class="indigo darken-4 py-8 white--text">
            <v-container>
                <div class="d-flex justify-space-between align-center">
                    <div class="d-flex align-center">
                        <h2 class="text-h4 font-weight-medium mr-4">
                            Your Balance
                        </h2>
                        <v-btn dark color="blue darken-3 font-weight-bold mr-4" @click="addEntryDialog = true">
                            <v-icon class="mr-1">mdi-plus</v-icon>
                            Add Entry
                        </v-btn>

                        <v-btn dark color="blue darken-3 font-weight-bold">
                            <v-icon class="mr-1">mdi-upload</v-icon>
                            Import CSV
                        </v-btn>
                    </div>

                    <div class="text-center">
                        <h2 class="text-uppercase indigo--text text--lighten-4">
                            Total Balance
                        </h2>
                        <span class="green--text text-h4 font-weight-bold">
                            $1,725.00
                        </span>
                    </div>

                </div>
            </v-container>
        </div>
        <v-container>

        asdasdasd
        </v-container>

        <v-dialog v-model="addEntryDialog" width="500">
            <v-card>
                <v-card-title class="mb-4">Add Balance Entry</v-card-title>
                <v-card-text>
                    <v-text-field outlined label="Label*" placeholder="Groceries...">
                    </v-text-field>
                    <v-row>
                        <v-col cols="6">
                            <v-menu :close-on-content-click="false" ref="dateMenu" v-model="dateMenu" :return-value.sync="fields.date.value" offset-y width="300px">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-bind="attrs" v-on="on" readonly v-model="fields.date.value" outlined label="Date*" placeholder="20 June, 2020"></v-text-field>
                                </template>
                                <v-date-picker v-model="fields.date.value" no-title scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="dateMenu = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.dateMenu.save(fields.date.value)">OK</v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="6">
                            <v-menu :close-on-content-click="false" ref="timeMenu" v-model="timeMenu" :return-value.sync="fields.time.value" offset-y width="300px">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-bind="attrs" v-on="on" readonly v-model="fields.time.value" outlined label="Time*" placeholder="8:00 AM"></v-text-field>
                                </template>
                                <v-time-picker v-model="fields.time.value">
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="timeMenu = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.timeMenu.save(fields.time.value)">OK</v-btn>
                                </v-time-picker>
                            </v-menu>

                        </v-col>
                    </v-row>
                    <v-text-field outlined label="Amount*" placeholder="500.00"></v-text-field>
                </v-card-text>
                <v-card-actions class="pa-6">
                    <v-spacer></v-spacer>
                    <v-btn class="font-weight-bold mr-2" large depressed dark @click="addEntryDialog = false" color="blue lighten-3">Cancel</v-btn>
                    <v-btn class="font-weight-bold" large depressed dark type="submit" color="blue darken-3">Save Entry</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script lang="ts">
    import { Component, Watch } from 'vue-property-decorator';
    import BaseClass from '@/base';

    @Component
    export default class App extends BaseClass {
        addEntryDialog = false;
        dateMenu = false;
        timeMenu = false;

        fields = {
            date: {
                value: null
            },
            time: {
                value: null
            }
        }
    }
</script>

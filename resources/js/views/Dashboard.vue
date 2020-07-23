<template>
    <div>
        <div class="indigo darken-4 py-8 white--text">
            <v-container>
                <div class="d-flex justify-space-between align-center">
                    <div class="d-flex align-center">
                        <h2 class="text-h4 font-weight-medium mr-4">
                            Your Balance
                        </h2>
                        <v-btn dark color="blue darken-3 font-weight-bold mr-4" @click="addEntryDialog.enabled = true">
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
                        <span :class="['text-h4 font-weight-bold', {
                            'green--text': totalBalance > 0,
                            'red--text': totalBalance < 0
                        }]">
                            {{ totalBalance | currencyFormat }}
                        </span>
                    </div>

                </div>
            </v-container>
        </div>
        <v-container>
            <div v-if="loading" style="height: calc(100vh - 228px);" class="d-flex align-center justify-center">
                <v-progress-circular indeterminate color="blue"></v-progress-circular>
            </div>
            <template v-else>
                <div class="my-6" v-for="(day, i) in Object.keys(transactions)" :key="`day-${i}`">
                    <v-row align="center">
                        <v-col cols="6" class="pl-6 font-weight-bold grey--text text-uppercase">
                            {{ day }}
                        </v-col>
                        <v-col cols="6" class="pr-6 text-right font-weight-bold grey--text text-uppercase text-h6">
                            <span :class="[{
                                'green--text': transactions[day].amount > 0,
                                'red--text': transactions[day].amount < 0
                            }]">
                                {{ transactions[day].amount | currencyFormat }}
                            </span>
                        </v-col>
                    </v-row>
                    <v-card v-for="(transaction, x) in transactions[day].data" :key="`transaction-${x}`" class="rounded-lg mb-6 custom-shadow">
                        <v-list class="px-4">
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title class="font-weight-bold text-h6">
                                        {{ transaction.label }}
                                    </v-list-item-title>
                                    <v-list-item-subtitle class="grey--text">
                                        {{ transaction.date.format('DD, MMMM YYYY [at] LT') }}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                                <v-list-item-action-text class="text-h6">
                                    <span v-if="transaction.type === 'debit'" class="red--text">
                                         -{{ transaction.amount | currencyFormat }}
                                    </span>
                                    <span v-else>
                                        +{{ transaction.amount | currencyFormat }}
                                    </span>
                                </v-list-item-action-text>
                            </v-list-item>
                        </v-list>
                    </v-card>
                </div>
            </template>
        </v-container>

        <v-dialog v-model="addEntryDialog.enabled" width="500" persistent>
            <v-card>
                <v-form ref="addEntryForm" @submit.prevent="addEntry()">
                    <v-card-title class="mb-4">Add Balance Entry</v-card-title>
                    <alert-messages class="ma-2"></alert-messages>
                    <v-card-text>
                        <v-text-field :rules="fields.label.rule" v-model="fields.label.value" outlined label="Label*" placeholder="Groceries...">
                        </v-text-field>
                        <v-row>
                            <v-col cols="6">
                                <v-menu :close-on-content-click="false" ref="dateMenu" v-model="addEntryDialog.dateMenu" :return-value.sync="fields.date.value" offset-y width="300px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field :rules="fields.date.rule" v-bind="attrs" v-on="on" readonly v-model="fields.date.value" outlined label="Date*" placeholder="2020-01-01"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="fields.date.value" no-title scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn text color="primary" @click="addEntryDialog.dateMenu = false">Cancel</v-btn>
                                        <v-btn text color="primary" @click="$refs.dateMenu.save(fields.date.value)">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="6">
                                <v-menu :close-on-content-click="false" ref="timeMenu" v-model="addEntryDialog.timeMenu" :return-value.sync="fields.time.value" offset-y width="300px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field :rules="fields.time.rule" v-bind="attrs" v-on="on" readonly v-model="fields.time.value" outlined label="Time*" placeholder="8:00"></v-text-field>
                                    </template>
                                    <v-time-picker v-model="fields.time.value">
                                        <v-spacer></v-spacer>
                                        <v-btn text color="primary" @click="addEntryDialog.timeMenu = false">Cancel</v-btn>
                                        <v-btn text color="primary" @click="$refs.timeMenu.save(fields.time.value)">OK</v-btn>
                                    </v-time-picker>
                                </v-menu>

                            </v-col>
                        </v-row>
                        <v-text-field :rules="fields.amount.rule" v-model="fields.amount.value" outlined label="Amount*" placeholder="500.00"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="pa-6">
                        <v-spacer></v-spacer>
                        <v-btn :disabled="addEntryDialog.loading" class="font-weight-bold mr-2" large depressed dark @click="addEntryDialog.enabled = false" color="blue lighten-3">Cancel</v-btn>
                        <v-btn :loading="addEntryDialog.loading" class="font-weight-bold" large depressed dark type="submit" color="blue darken-3">Save Entry</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>

        </v-dialog>
    </div>
</template>
<script lang="ts">
    import { Component, Watch } from 'vue-property-decorator';
    import BaseClass from '@/base';
    import AlertMessages from "@/components/AlertMessages.vue";
    import Transaction from "@/models/transaction";
    import config from '@/config';
    import moment from 'moment';

    interface DatabaseTransactionRecord {
        id: number;
        label: string;
        amount: number;
        date: string;
        created_at: string;
        updated_at: string;
    }

    @Component({
        components: {AlertMessages}
    })
    export default class App extends BaseClass {
        $refs!: {
            addEntryForm: HTMLFormElement;
        }
        totalBalance = 0;
        addEntryDialog = {
            enabled: false,
            loading: false,
            dateMenu: false,
            timeMenu: false,
        };

        fields = {
            label: {
                value: '',
                rule: [
                    (v: string) => v && v.length > 0 || 'Invalid label.'
                ]
            },
            date: {
                value: null,
                rule: [
                    (v: string) => v && /([0-9]+){4}-([0-9]+){2}-([0-9]+){2}/.test(v) || 'Invalid date format.'
                ]
            },
            time: {
                value: null,
                rule: [
                    (v: string) => v && /([0-9]+){2}:([0-9]+){2}/.test(v) || 'Invalid time format.'
                ]
            },
            amount: {
                value: 0.00,
                rule: [
                    (v: string) => v && Number.parseInt(v) !== 0 || 'Amount cannot be equal to zero.',
                    (v: string) => v && v.length > 0 || 'Invalid amount.'
                ]
            }
        }

        transactions: {
            [index: string]: any;
        } = {};

        created() {
         this.loadTransactions();
        }

        private async loadTransactions() {
            const now = moment();
            this.loading = true;
            try {
                const transactions = await this.$axios.get(config.endpoints.transactions);

                this.transactions = {};
                this.totalBalance = 0;
                transactions.data.data.forEach((item: DatabaseTransactionRecord) => {
                    const transaction = new Transaction(item);
                    const calculatedAmount = transaction.type === 'debit' ? -Math.abs(transaction.amount) : transaction.amount;
                    this.totalBalance += calculatedAmount;

                    let day;
                    if (now.diff(transaction.date, 'days') < 2) {
                        day = transaction.date.calendar(null, {
                            sameDay: '[Today]',
                            nextDay: '[Tomorrow]',
                            lastDay: '[Yesterday]',
                        });
                    }
                    else {
                        day = transaction.date.format('ddd, D MMMM');
                    }

                    if (! this.transactions[day]) {
                        Object.assign(this.transactions, {
                            [day]: {
                                amount: calculatedAmount,
                                data: [transaction]
                            }
                        });
                    } else {
                        this.transactions[day].data.push(transaction);
                        this.transactions[day].amount += calculatedAmount;
                    }


                });
                console.log(this.transactions);

            } catch(error) {

                this.handleError(error);
            }
            this.loading = false;
        }

        private async addEntry() {
            this.clearAlertMessage();
            if (! this.$refs.addEntryForm.validate()) {
                return;
            }

            this.addEntryDialog.loading = true;
            try {
                const transaction = new Transaction({
                    label: this.fields.label.value,
                    date: `${this.fields.date.value} ${this.fields.time.value}`,
                    amount: this.fields.amount.value,
                });
                await transaction.save();

                this.$refs.addEntryForm.reset();
                this.loadTransactions();
                this.addEntryDialog.enabled = false;
            }
            catch(error) {
                this.handleError(error);
            }
            this.addEntryDialog.loading = false;
        }


    }
</script>

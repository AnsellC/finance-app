<template>
    <div>
        <div class="indigo darken-4 py-8 white--text">
            <v-container>
                <v-row>
                    <v-col cols="12" md="6" class="text-center text-md-left">

                        <v-row align="center">
                            <v-col cols="12" md="5">
                                <h2 class="text-h4 font-weight-medium">
                                    Your Balance
                                </h2>
                            </v-col>
                            <v-col cols="12" md="7">
                                <v-btn class="mr-1" dark color="blue darken-3 font-weight-bold" @click="addEntryDialog.enabled = true">
                                    <v-icon class="mr-1">mdi-plus</v-icon>
                                    Add Entry
                                </v-btn>
                                <v-btn dark color="blue darken-3 font-weight-bold">
                                    <v-icon class="mr-1">mdi-upload</v-icon>
                                    Import CSV
                                </v-btn>
                            </v-col>


                        </v-row>
                    </v-col>
                    <v-col cols="12" md="6" class="text-center text-md-right">
                        <h2 class="text-uppercase indigo--text text--lighten-4">
                            Total Balance
                        </h2>
                        <span :class="['text-h4 font-weight-bold', {
                            'green--text': totalBalance > 0,
                            'red--text': totalBalance < 0
                        }]">
                            {{ totalBalance | currencyFormat }}
                        </span>
                    </v-col>
                </v-row>

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
                    <v-hover v-slot:default="{ hover }" v-for="(transaction, x) in transactions[day].data" :key="`transaction-${x}`">
                        <v-card class="rounded-lg mb-6 custom-shadow">
                            <v-row no-gutters align="center" class="pa-4">
                                <v-col order="1" cols="12" md="8" class="text-center text-md-left">
                                    <v-list color="transparent" class="pa-0">
                                        <v-list-item>
                                            <v-list-item-content>
                                                <v-list-item-title class="font-weight-bold text-h6">
                                                    {{ transaction.label }}
                                                </v-list-item-title>
                                                <v-list-item-subtitle class="grey--text">
                                                    {{ transaction.date.format('DD, MMMM YYYY [at] LT') }}
                                                </v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>

                                </v-col>

                                <v-col order="3" order-md="2" cols="12" md="2" :class="[
                                    {'mt-12 mt-md-0': hover}, 'text-center text-md-right'
                                    ]">
                                    <span v-if="hover">
                                        <v-btn icon class="mr-2" @click="editTransaction(transaction)">
                                            <v-icon>mdi-pencil</v-icon>
                                        </v-btn>
                                        <v-btn icon class="mr-2" @click="deleteTransaction(transaction)">
                                            <v-icon>mdi-delete</v-icon>
                                        </v-btn>
                                    </span>
                                </v-col>
                                <v-col order="2" order-md="3" cols="12" md="2" class="text-center text-md-right text-h6">
                                      <span v-if="transaction.type === 'debit'" class="red--text">
                                             -{{ transaction.amount | currencyFormat }}
                                        </span>
                                    <span v-else>
                                            +{{ transaction.amount | currencyFormat }}
                                        </span>
                                </v-col>
                            </v-row>


                                <template v-if="editing.transaction.id === transaction.id">

                                    <v-divider></v-divider>
                                    <div class="pa-6">
                                        <v-row>
                                            <v-col cols="12" md="8" offset-md="2">
                                                <v-row>
                                                    <v-col cols="12" md="6">
                                                        <v-text-field
                                                            v-model="editing.label"
                                                            outlined
                                                            label="Label*">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="12" md="6">
                                                        <v-text-field
                                                            v-model="editing.amount"
                                                            outlined
                                                            label="Amount*">
                                                        </v-text-field>
                                                    </v-col>
                                                    <v-col cols="6">
                                                        <v-menu
                                                            :close-on-content-click="false"
                                                            v-model="editing.dateMenu"
                                                            offset-y
                                                            min-width="290px">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field
                                                                    :rules="fields.date.rule"
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    readonly
                                                                    v-model="editing.date"
                                                                    outlined
                                                                    label="Date*"
                                                                    placeholder="2020-01-01">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker
                                                                v-model="editing.date"
                                                                scrollable
                                                                @input="editing.dateMenu = false">
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>
                                                    <v-col cols="6">
                                                        <v-menu
                                                            :close-on-content-click="false"
                                                            v-model="editing.timeMenu"
                                                            offset-y
                                                            min-width="290px">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field
                                                                    :rules="fields.time.rule"
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    readonly
                                                                    v-model="editing.time"
                                                                    outlined label="Time*"
                                                                    placeholder="8:00">
                                                                </v-text-field>
                                                            </template>
                                                            <v-time-picker v-model="editing.time">
                                                                <v-spacer></v-spacer>
                                                                <v-btn text
                                                                       color="primary"
                                                                       @click="editing.timeMenu = false">
                                                                    Done
                                                                </v-btn>
                                                            </v-time-picker>
                                                        </v-menu>
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </div>
                                    <v-divider></v-divider>
                                    <div class="pa-6 text-right">
                                        <v-btn :disabled="editing.loading" class="font-weight-bold mr-2" large depressed dark @click="editTransaction(null)" color="blue lighten-3">Cancel</v-btn>
                                        <v-btn @click="saveEdit()" :loading="editing.loading" class="font-weight-bold" large depressed dark color="blue darken-3">Update Entry</v-btn>
                                    </div>

                                </template>



                        </v-card>
                    </v-hover>
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
                                <v-menu :close-on-content-click="false" v-model="addEntryDialog.dateMenu" offset-y min-width="290px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field :rules="fields.date.rule" v-bind="attrs" v-on="on" readonly v-model="fields.date.value" outlined label="Date*" placeholder="2020-01-01"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="fields.date.value" scrollable @input="addEntryDialog.dateMenu = false">
                                    </v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="6">
                                <v-menu :close-on-content-click="false" ref="timeMenu" v-model="addEntryDialog.timeMenu" offset-y min-width="290px">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field :rules="fields.time.rule" v-bind="attrs" v-on="on" readonly v-model="fields.time.value" outlined label="Time*" placeholder="8:00"></v-text-field>
                                    </template>
                                    <v-time-picker v-model="fields.time.value">
                                        <v-spacer></v-spacer>
                                        <v-btn text color="primary" @click="addEntryDialog.timeMenu = false">Done</v-btn>
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

        <v-dialog v-model="deleteTransactionDialog.enabled" width="500">
            <v-card>
                <v-card-title>
                    Delete confirmation
                </v-card-title>
                <v-card-text>
                    Are you sure you want to delete <strong>{{ deleteTransactionDialog.transaction.label }}</strong>?
                </v-card-text>
                <v-card-actions class="pa-6">
                    <v-spacer></v-spacer>
                    <v-btn :disabled="deleteTransactionDialog.loading" class="font-weight-bold mr-2" large depressed dark @click="deleteTransactionDialog.enabled = false" color="blue lighten-3">Cancel</v-btn>
                    <v-btn @click="confirmDeleteTransaction()" :loading="deleteTransactionDialog.loading" class="font-weight-bold" large depressed dark color="blue darken-3">Yes, Delete it</v-btn>
                </v-card-actions>
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

        deleteTransactionDialog = {
            enabled: false,
            loading: false,
            transaction: {} as Transaction,
        };

        editing = {
            loading: false,
            label: '',
            dateMenu: false,
            timeMenu: false,
            date: '',
            time: '',
            amount: 0,
            transaction: {} as Transaction
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

        private deleteTransaction(transaction: Transaction) {
            this.deleteTransactionDialog.enabled = true;
            this.deleteTransactionDialog.transaction = transaction;
        }

        private async confirmDeleteTransaction() {
            this.deleteTransactionDialog.loading = true;
            try {
                await this.deleteTransactionDialog.transaction.delete();
                this.loadTransactions();
            }
            catch(error) {
                this.handleError(error);
            }
            this.deleteTransactionDialog.loading = false;
            this.deleteTransactionDialog.enabled = false;

        }

        private editTransaction(transaction: Transaction | null) {
            if (!transaction) {
                this.resetEditingModel();
                return;
            }

            this.editing = {
                loading: false,
                amount: transaction.type === 'debit' ? -Math.abs(transaction.amount) : transaction.amount,
                label: transaction.label,
                timeMenu: false,
                dateMenu: false,
                transaction: transaction,
                date: transaction.date.clone().format('YYYY-MM-DD'),
                time: transaction.date.clone().format('HH:mm')
            }
        }

        private async saveEdit() {
            const transaction = this.editing.transaction;
            transaction.label = this.editing.label;
            transaction.amount = Math.abs(this.editing.amount);
            transaction.type = this.editing.amount > 0 ? 'credit' : 'debit';
            transaction.date = moment(`${this.editing.date} ${this.editing.time}`);
            this.editing.loading = true;
            try {
                await transaction.save();
                this.resetEditingModel();
                this.loadTransactions();
            }catch(error) {
                this.handleError(error);
            }
            this.editing.loading = false;

        }


        private resetEditingModel() {
            this.editing = {
                loading: false,
                amount: 0,
                label: '',
                timeMenu: false,
                dateMenu: false,
                transaction: {} as Transaction,
                date: '',
                time: '',
            }
        }

    }
</script>

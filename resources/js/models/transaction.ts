import moment from 'moment';
import axios from '@/plugins/axios';
import config from '@/config';
import { AxiosResponse } from 'axios';

export default class Transaction {
    public id = 0;
    public label = '';
    public date = moment();
    public amount = 0;
    public created_at = moment();
    public updated_at = moment();
    public type: TransactionType = 'credit';
    private dates = ['date', 'updated_at', 'created_at'];

    constructor(data: {
        id?: number;
        label: string;
        date: string;
        amount: number;
        created_at?: string;
        updated_at?: string;
        [index: string]: any;
    }) {
        const keys = Object.keys(data);
        keys.forEach(key => {
            let value;

            if (this.dates.includes(key)) {
                value = moment(data[key]);
            } else {
                value = data[key];
            }

            Object.assign(this, {
                [key]: value
            });
        });

        return this;
    }

    public async save(): Promise<AxiosResponse<any>> {
        const endpoint = config.endpoints.transactions;
        const postData = {
            label: this.label,
            amount: this.amount,
            date: this.date.format('YYYY-MM-DD HH:mm')
        };

        if (this.id !== 0 && this.id) {
            return axios.put(`${endpoint}/${this.id}`, postData);
        }

        return axios.post(endpoint, postData);
    }

    public async delete(): Promise<AxiosResponse<any>> {
        return axios.delete(`${config.endpoints.transactions}/${this.id}`);
    }
}

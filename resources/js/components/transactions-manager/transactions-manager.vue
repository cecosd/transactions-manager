<template>
    <div>
        <div class="col-lg-3">
            <div class="input-group mb-3">
                <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Type here..." 
                    aria-label="Type here"
                    v-model="search_query" 
                    >

                <div class="input-group-append">
                    <div class="btn btn-outline-primary" >Search</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="btn-group">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sort
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" @click="sortById('asc')">ID Asc</a>
                    <a class="dropdown-item" @click="sortById('desc')">ID Desc</a>
                    <a class="dropdown-item" @click="sortByName('asc')">Name Asc</a>
                    <a class="dropdown-item" @click="sortByName('desc')">Name Desc</a>
                </div>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">User(Balance)</th>
                    <th scope="col">Type</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    v-for="transaction in formattedTransactions" :key="transaction.id"
                    :class="{'table-danger': transaction.is_debit, 'table-success': transaction.is_credit}"
                >
                    <td>{{transaction.id}}</td>
                    <td>{{transaction.account_user_name}}({{transaction.account_balance}})</td>
                    <td>{{transaction.type}}</td>
                    <td>{{transaction.formatted_amount}}</td>
                </tr>
            </tbody>
        </table>  

        <div 
            v-if="new_transaction_mode"
            class="row"
        >
            <div class="col-lg-3">
                <select v-model="new_transaction.user_email" class="form-control">
                    <option 
                        v-for="(list_item_name, list_item_email) in accounts_dropdown_list" 
                        :value="list_item_email"
                        :key="list_item_email"
                    >{{list_item_name}}</option>
                </select>
            </div>
            <div class="col-lg-3">
                <select v-model="new_transaction.type" class="form-control">
                    <option value="Debit">Debit</option>
                    <option value="Credit">Credit</option>
                </select>
            </div>
            <div class="col-lg-3">
                <input type="number" min="1" v-model="new_transaction.amount" class="form-control" placeholder="Amount" aria-label="Amount">
            </div>
            <div class="col-lg-3">
                <button class="btn btn-primary" @click="addTransaction()">Submit</button>
            </div>
        </div>
        <button v-if="!new_transaction_mode" class="btn btn-primary" @click="toggleNewMode()">
            Add transaction
        </button>
        <ul>
            <li v-for="(error, error_key) in validationErrors" :key="error_key">{{error[0]}}</li>
        </ul>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: 'TransactionsManager',
    props: {
        data_route: {
            type: String,
            required: true
        },
        create_route: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            transactions: [],
            accounts_dropdown_list: {},
            search_query: '',
            new_transaction_mode: false,
            new_transaction: {
                amount: null,
                type: null,
                user_email: null,
            },
            validationErrors: [],
        }
    },
    computed: {
        formattedTransactions: function(){
            var vm = this;
            var query = vm.search_query;
            return this.transactions.filter(function(transaction){
                
                vm.accounts_dropdown_list[transaction.account_user_email] = transaction.account_user_name;
                
                return (transaction.amount.indexOf(query) > -1
                        || transaction.account_user_name.indexOf(query) > -1
                        || transaction.account_user_email.indexOf(query) > -1
                        || transaction.type.indexOf(query) > -1) ? true : false;
            });
        }
    },
    methods: {
        getData: function(){
            axios
                .get(this.data_route)
                .then(response => {
                    this.transactions = response.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        isDebit(type){
            return (type == 'Debit');
        },
        isCredit(type){
            return (type == 'Credit');
        },
        sortByName: function(order)
        {
            if(order == 'asc')
                return this.transactions.sort((a, b) => (a.account_user_name < b.account_user_name) ? 1 : -1);
            else if(order == 'desc')
                return this.transactions.sort((a, b) => (a.account_user_name > b.account_user_name) ? 1 : -1);
            else 
                return this.transactions;
        },
        sortById: function(order)
        {
            if(order == 'asc')
                return this.transactions.sort((a, b) => (a.id > b.id) ? 1 : -1);
            else if(order == 'desc')
                return this.transactions.sort((a, b) => (a.id < b.id) ? 1 : -1);
            else 
                return this.transactions;
        },
        toggleNewMode: function(){
            this.clearFormValidationErrors();
            this.new_transaction_mode = !this.new_transaction_mode;
        },
        clearFormValidationErrors: function(){
            this.validationErrors = [];
        },
        clearAddTransactionForm: function(){
            for(var index in this.new_transaction)
            {
               this.new_transaction[index] = null;
            }
        },
        addTransaction: function(){
            this.toggleNewMode();
            
            axios
                .post(this.create_route, this.new_transaction)
                .then(response => {
                    
                    // this.transactions.push(response.data);
                })
                .catch(error => {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                    }
                })
                .finally(() => {
                    this.clearAddTransactionForm();
                });
        }
    },
    created(){
        this.getData();
    }
}
</script>

<style scoped>
    
</style>
<template>
    <div>
        <div class="row">
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
            <div class="col-lg-9">
                <div class="btn-group float-lg-right">
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
        </div>
        
        <component 
            :is="'data-table'"
            @singleTransactionUpdated="singleTransactionUpdated"
            @singleTransactionDeleted="singleTransactionDeleted"
            :transactions="transactions"
            :search_query="search_query"
            :update_route="update_route"
            :delete_route="delete_route"
        ></component>

        <component 
            :is="'new-record-form'"
            :accounts="accounts_dropdown_list"
            :create_route="create_route"
            @clearSearchForm="clearSearchForm"
            @newTransactionAdded="addToTransactions"
        ></component>

    </div>
</template>

<script>
import axios from 'axios';
import dataTable from './components/dataTable.vue';
import newRecordForm from './components/newRecordForm.vue';
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
        update_route: {
            type: String,
            required: true
        },
        delete_route: {
            type: String,
            required: true
        },
        accounts_route: {
            type: String,
            required: true
        },
    },
    components: {
        dataTable,
        newRecordForm
    },
    data() {
        return {
            transactions: [],
            accounts_dropdown_list: {},
            search_query: '',
        }
    },
    methods: {
        getTransactions: function(){
            axios
                .get(this.data_route)
                .then(response => {
                    this.transactions = response.data.original;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        getAccounts: function(){
            axios
                .get(this.accounts_route)
                .then(response => {
                    this.accounts_dropdown_list = response.data.original;
                })
                .catch(err => {
                    console.log(err);
                });
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
        clearSearchForm: function(){
            this.search_query = '';
        },
        addToTransactions: function(data)
        {
            this.transactions.push(data.transaction);
        },
        singleTransactionUpdated: function(data)
        {
            this.transactions[data.index] = data.transaction;
        },
        singleTransactionDeleted: function(index)
        {
            this.transactions.splice(index, 1);
        }
    },
    created(){
        this.getTransactions();
        this.getAccounts();
    }
}
</script>

<style scoped>
    
</style>
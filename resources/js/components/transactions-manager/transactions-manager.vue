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
            :transactions="formattedTransactions"
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
        }
    },
    created(){
        this.getData();
    }
}
</script>

<style scoped>
    
</style>
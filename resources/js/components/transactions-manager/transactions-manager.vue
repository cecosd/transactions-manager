<template>
    <div>
        <div class="col-lg-3">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Type here..." aria-label="Type here" aria-describedby="button-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button" id="button-addon2">Search</button>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="btn-group">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sort
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">ID Asc</a>
                    <a class="dropdown-item" href="#">ID Desc</a>
                    <a class="dropdown-item" href="#">Name Asc</a>
                    <a class="dropdown-item" href="#">Name Desc</a>
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
                <tr v-for="transaction in transactions" :key="transaction.id">
                    <td>{{transaction.id}}</td>
                    <td>{{transaction.transaction_account.user.name}}({{transaction.transaction_account.balance}})</td>
                    <td>{{transaction.type}}</td>
                    <td>{{transaction.amount}}</td>
                </tr>
            </tbody>
        </table>  

        <button class="btn btn-primary" type="button">
            Add transaction
        </button>
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
        }
    },
    data() {
        return {
            transactions: []
        }
    },
    computed: {

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
    },
    created(){
        this.getData();
    }
}
</script>

<style scoped>
    
</style>
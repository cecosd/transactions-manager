<template>
    <div>
        <div v-if="show_form" class="row">
            <div class="col-lg-3">
                <select v-model="transaction.user_email" class="form-control">
                    <option 
                        v-for="account in accounts" 
                        :value="account.email"
                        :key="account.email"
                    >{{account.name}} | {{account.email}}</option>
                </select>
            </div>
            <div class="col-lg-3">
                <select v-model="transaction.type" class="form-control">
                    <option value="Debit">Debit</option>
                    <option value="Credit">Credit</option>
                </select>
            </div>
            <div class="col-lg-3">
                <input type="number" min="1" v-model="transaction.amount" class="form-control" placeholder="Amount" aria-label="Amount">
            </div>
            <div class="col-lg-3">
                <button class="btn btn-primary" @click="addTransaction()">Submit</button>
                <button class="btn btn-default" @click="hideForm()">Discard</button>
            </div>
        </div>
        <button v-if="!show_form" class="btn btn-primary" @click="toggleNewMode()">
            <font-awesome-icon icon="pencil-alt" /> Add transaction
        </button>
        <ul>
            <li v-for="(error, error_key) in validationErrors" :key="error_key">{{error[0]}}</li>
        </ul>
    </div>
</template>

<script>
import { setTimeout } from 'timers';
export default {
    name: 'newRecordForm',
    props: {
        accounts: {
            type: [Array, Object],
            required: true  
        },
        create_route: {
            type: String,
            required: true
        },
    },
    data(){
        return {
            transaction: {
                amount: null,
                type: null,
                user_email: null,
            },
            validationErrors: [],
            show_form: false
        }
    },
    methods: {
        addTransaction: function(){
            this.toggleNewMode();
            
            axios
                .post(this.create_route, this.transaction)
                .then(response => {
                    this.$emit('newTransactionAdded', {
                        transaction: response.data.original
                    });
                })
                .catch(error => {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors;
                    }
                })
                .finally(() => {
                    this.$emit('clearSearchForm');
                    this.clearAddTransactionForm();
                });
        },
        toggleNewMode: function(){
            this.clearFormValidationErrors();
            this.show_form = !this.show_form;
        },
        clearFormValidationErrors: function(){
            this.validationErrors = [];
        },
        clearAddTransactionForm: function(){
            for(var index in this.transaction)
            {
               this.transaction[index] = null;
            }
        },
        hideForm: function(){
            this.clearAddTransactionForm();
            this.toggleNewMode();
        },
    }
}
</script>

<style>
    
</style>
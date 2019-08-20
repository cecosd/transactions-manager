<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">User(Balance)</th>
                    <th scope="col">Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    v-for="(transaction, transaction_index) in formattedTransactions" :key="transaction.id"
                    :class="{'table-danger': transaction.is_debit, 'table-success': transaction.is_credit}"
                >
                    <td>{{transaction.id}}</td>
                    <td>{{transaction.account_user_name}} ({{transaction.account_balance}})</td>
                    <td>
                        <div v-if="transaction_index != edit_form.index" v-on:dblclick="counter += 1, edit(transaction_index, 'type')">
                            {{transaction.type}}
                        </div>
                        <div v-if="edit_mode && transaction_index == edit_form.index && edit_form.field == 'type'">
                            <div class="input-group">
                                <select v-model="edit_form.value" class="custom-select form-control">
                                    <option :value="transaction.type" selected>{{transaction.type}}</option>
                                    <option v-if="transaction.type != 'Debit'" value="Debit">Debit</option>
                                    <option v-if="transaction.type != 'Credit'" value="Credit">Credit</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" @click="confirmChange()" type="button"><font-awesome-icon icon="check" /></button>
                                    <button class="btn btn-outline-secondary" @click="discardChange()" type="button"><font-awesome-icon icon="times" /></button>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{transaction.formatted_amount}}</td>
                    <td><button class="btn btn-danger float-lg-right" @click="deleteRecord(transaction_index)" type="button"><font-awesome-icon icon="trash" /></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: 'dataTable',
    props: {
        transactions: {
            type: Array,
            required: true,
        },
        update_route: {
            type: String,
            required: true
        },
        delete_route: {
            type: String,
            required: true
        },
        search_query: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            counter: 0,
            edit_mode: false,
            edit_form: {
                index: null,
                field: null,
                value: null
            },
            
        }
    },
    computed: {
        formattedTransactions: function(){
            var vm = this;
            var query = vm.search_query;
            return this.transactions.filter(function(transaction){              
                return (transaction.amount.indexOf(query) > -1
                        || transaction.account_user_name.indexOf(query) > -1
                        || transaction.account_user_email.indexOf(query) > -1
                        || transaction.type.indexOf(query) > -1) ? true : false;
            });
        }
    },
    methods: {
        edit: function(transaction_index, field){
            this.edit_mode = true;
            this.edit_form.index = transaction_index;
            this.edit_form.field = field;
        },
        clearForm: function()
        {
            this.edit_mode = false;
            this.edit_form.index = null;
            this.edit_form.field = null;
            this.edit_form.value = null;
        },
        confirmChange: function()
        {
            var transaction = this.transactions[this.edit_form.index];
            transaction[this.edit_form.field] = this.edit_form.value;
            
            axios
                .put(this.update_route, transaction)
                .then(response => {
                    this.$emit('singleTransactionUpdated', {
                        index: this.edit_form.index,
                        transaction: response.data.original
                    });
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.clearForm();
                });
        },
        discardChange: function()
        {
            this.clearForm();
        },
        deleteRecord: function(index)
        {
            var transaction = this.transactions[index];
            
            axios
                .delete(this.delete_route + '/' + transaction.id)
                .then(response => {
                    this.$emit('singleTransactionDeleted',index);
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.clearForm();
                });
        },
    }
}
</script>

<style>
    
</style>
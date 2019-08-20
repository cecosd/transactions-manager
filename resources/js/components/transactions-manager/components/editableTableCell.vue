<template>
    <div>
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
                    v-for="(transaction, transaction_index) in transactions" :key="transaction.id"
                    :class="{'table-danger': transaction.is_debit, 'table-success': transaction.is_credit}"
                >
                    <td>{{transaction.id}}</td>
                    <td>{{transaction.account_user_name}} ({{transaction.account_balance}})</td>
                    <td>
                        <div v-if="transaction_index != edit_form.index" v-on:dblclick="counter += 1, edit(transaction_index, 'type')">
                            {{transaction.type}}
                        </div>
                    </td>
                    <td>{{transaction.formatted_amount}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import editableTableCell from './editableTableCell.vue';
export default {
    name: 'dataTable',
    props: {
        transactions: {
            type: Array,
            required: true,
        }
    },
    data() {
        return {
            counter: 0,
            edit_mode: false,
            edit_form: {
                index: null,
                field: null
            },
            
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
        },
        confirmChange: function()
        {
            var transaction = this.transactions[this.edit_form.index];
            transaction[this.edit_form.field]
        },
        discardChange: function()
        {
            this.clearForm();
        },
    }
}
</script>

<style>
    
</style>
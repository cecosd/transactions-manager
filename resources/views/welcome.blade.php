@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <transactions-manager
                data_route="{{route('transactions-manager-get-transactions')}}"
                accounts_route="{{route('transactions-manager-get-accounts')}}"
                create_route="{{route('transactions-manager-create-transaction')}}"
                update_route="{{route('transactions-manager-update-transaction')}}"
                delete_route="{{route('transactions-manager-delete-transaction')}}"
            ></transactions-manager>
        </div>
    </div>
</div>
@endsection

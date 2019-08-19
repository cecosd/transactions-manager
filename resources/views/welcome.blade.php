@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <transactions-manager
                data_route="{{route('transactions-manager-get-data')}}"
            ></transactions-manager>
        </div>
    </div>
</div>
@endsection

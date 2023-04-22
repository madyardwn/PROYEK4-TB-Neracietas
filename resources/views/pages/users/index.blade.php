@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" id="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
    @include('pages.users.actions')
    @include('pages.users.import')
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

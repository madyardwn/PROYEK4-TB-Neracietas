@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" id="card">
            <div class="card-header">Manajemen Permissions</div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
    @include('pages.permissions.actions')
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manajemen Departemen</div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
    @include('pages.departments.actions')
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

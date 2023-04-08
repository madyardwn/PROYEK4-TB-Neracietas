@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manajemen Kabinet</div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
    @include('pages.cabinets.actions')
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

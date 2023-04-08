@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Event</div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
    @include('pages.events.actions')
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

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
    <div class="form-group mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" id="role" name="role[]" multiple="multiple">
            <option value="" selected disabled>Pilih Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    @include('pages.users.actions')
    @include('pages.users.import')
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

    <script>
        $(document).ready(function() {
                    $('#role').select2();
    </script>
@endpush

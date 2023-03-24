@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">Manage Users</div>
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>

    @include('users.create')
    @include('users.edit')
</div>
@endsection
@push('scripts')
{!! $dataTable->scripts() !!}

<script>
    $(document).ready(function() {

        $ajaxSetup = $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $('#formCreate').on('submit', function(e) {
            e.preventDefault()
            $('.is-invalid').removeClass('is-invalid')
            $('.invalid-feedback').remove()

            const table = $('#users-table').DataTable()
            const form = $(this)
            const method = form.attr('method')
            const data = form.serialize()
            const url = form.attr('action')

            $.ajax({
                method: method,
                url: url,
                data: data,

                success: function(res) {
                    if (res) {
                        $('#modalCreate').modal('hide')
                        table.ajax.reload()
                    }
                },
                error: function(err) {
                    if (err) {
                        $.each(err.responseJSON.errors, function(key, value) {
                            $('#formCreate').find(`#${key}`).addClass('is-invalid')
                            $('#formCreate').find(`#${key}`).after(`<div class="invalid-feedback">${value}</div>`)
                        })
                    }
                }
            })
        })

        $(document).on('click', '.btnEdit', function() {
            const id = $(this).data('id')
            const method = $(this).data('method')

            $.ajax({
                method: method,
                url: `/users/${id}/edit`,
                data: {
                    id: id
                },
                success: function(res) {
                    if (res) {
                        $('#formEdit').trigger('reset')
                        $('#modalEdit').modal('show')
                        $('#formEdit').attr('action', `/users/${id}`)
                        $('#formEdit').find('input[name="name"]').val(res.name)
                        $('#formEdit').find('input[name="email"]').val(res.email)
                        $('#formEdit')
                            .find('option')
                            .each(function() {
                                if ($(this).val() == res.role_id) {
                                    $(this).attr('selected', true)
                                } else {
                                    $(this).attr('selected', false)
                                }
                            })
                    }
                },
            })
        })

        $('#formEdit').on('submit', function(e) {
            e.preventDefault()
            $('.is-invalid').removeClass('is-invalid')
            $('.invalid-feedback').remove()

            const table = $('#users-table').DataTable()
            const form = $(this)
            const method = form.attr('method')
            const data = form.serialize()
            const url = form.attr('action')

            $.ajax({
                method: method,
                url: url,
                data: data,

                success: function(res) {
                    if (res) {
                        $('#modalEdit').modal('hide')
                        table.ajax.reload()
                    }
                },
                error: function(err) {
                    if (err) {
                        $.each(err.responseJSON.errors, function(key, value) {
                            $('#formEdit').find(`#${key}`).addClass('is-invalid')
                            $('#formEdit').find(`#${key}`).after(`<div class="invalid-feedback">${value}</div>`)
                        })
                    }
                }
            })
        })

        $(document).on('click', '.btnDelete', function() {
            const id = $(this).data('id')

            $.ajax({
                method: 'DELETE',
                url: "{{ route('users.destroy', ':id') }}".replace(':id', id),
                data: {
                    id: id
                },
                success: function(res) {
                    const table = $('#users-table').DataTable()
                    table.ajax.reload()
                },
            })
        })
    })
</script>
@endpush

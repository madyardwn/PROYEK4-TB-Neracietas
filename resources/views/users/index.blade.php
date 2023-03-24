@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">Manage Users</div>
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formModal" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="avatar" class="form-label">Avatar</label>
                                    <img class="img-holder img-thumbnail" width="265" height="300" src="" alt="">
                                    <input type="file" class="form-control mt-2" id="avatar" name="avatar">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                </div>
                                <div class="form-group mb-3" id="pwd">
                                    <div class="row" id="password">
                                        <div class="col-md-6">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="role">Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="" selected disabled>Pilih Role</option>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button id="btnSubmit" type="submit" class="btn btn-primary ms-auto">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

        $('#avatar').change(function() {
            const file = $(this)[0].files[0]
            const reader = new FileReader()

            reader.onload = function(e) {
                $('.img-holder').attr('src', e.target.result)
            }

            reader.readAsDataURL(file)
        })

        $('#formModal').on('submit', function(e) {
            e.preventDefault();

            $('#btnSubmit').attr('disabled', 'disabled');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            const form = $(this);
            const formData = new FormData(form[0]);

            const table = $('#users-table').DataTable();
            const method = form.attr('method');
            const url = form.attr('action');

            $.ajax({
                method: method,
                url: url,
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {
                    if (res) {

                        $('#btnSubmit').removeAttr('disabled');
                        $('#modal').modal('hide');
                        table.ajax.reload();
                    }
                },
                error: function(err) {
                    if (err) {
                        $('#btnSubmit').removeAttr('disabled');
                        $.each(err.responseJSON.errors, function(key, value) {
                            $('#formModal').find(`#${key}`).addClass('is-invalid');
                            $('#formModal').find(`#${key}`).after(`<div class="invalid-feedback">${value}</div>`);
                        });
                    }
                }
            });
        });


        $(document).on('click', '.btnEdit', function() {
            $('.is-invalid').removeClass('is-invalid')
            $('.invalid-feedback').remove()

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
                        console.log(res)
                        $('#formModal').trigger('reset')
                        $('#formModal').find('#password').remove()
                        $('#modal').find('.modal-title').text('Edit User')
                        $('#modal').find('.modal-footer').find('button').text('Update')

                        $('#formModal').find('input[name="_method"]').val('PUT')
                        $('#formModal').find('input[name="name"]').val(res.name)
                        $('#formModal').find('input[name="email"]').val(res.email)
                        if (res.avatar) {
                            $('.img-holder').attr('src', `/storage/${res.avatar}`)
                        } else {
                            $('.img-holder').attr('src', '/img/default_avatar.jpg')
                        }

                        $('#modal').modal('show')
                        $('#formModal').attr('action', `/users/${id}`)


                        $('#formModal')
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

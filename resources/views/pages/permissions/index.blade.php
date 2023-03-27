@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage permissions</div>
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
                                        <img class="img-holder img-thumbnail" width="265" height="300" src=""
                                            alt="">
                                        <input type="file" class="form-control mt-2" id="avatar" name="avatar">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Masukkan Nama">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email') }}" placeholder="Masukkan Email">
                                    </div>
                                    <div class="form-group mb-3" id="pwd">
                                        <div class="row" id="password">
                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="password_confirmation" class="form-label">Password
                                                    Confirmation</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="role" class="form-label">Role</label>
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
                            <div class="d-flex justify-content-between w-100">
                                <a href="#" class="btn" data-bs-dismiss="modal">Tutup</a>
                                <button id="btnSubmit" type="submit" class="btn btn-primary ms-auto">Simpan</button>
                            </div>
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

                $('#btnSubmit')
                    .addClass('btn-loading')
                    .attr('disabled', true);
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                const form = $(this);
                const formData = new FormData(form[0]);

                const table = $('#permissions-table').DataTable();
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

                            $('#btnSubmit')
                                .removeClass('btn-loading')
                                .attr('disabled', false);
                            $('#modal').modal('hide');
                            table.ajax.reload();
                        }
                    },
                    error: function(err) {
                        if (err) {
                            $('#btnSubmit')
                                .removeClass('btn-loading')
                                .attr('disabled', false);
                            $.each(err.responseJSON.errors, function(key, value) {
                                $('#formModal').find(`#${key}`).addClass('is-invalid');
                                $('#formModal').find(`#${key}`).after(
                                    `<div class="invalid-feedback">${value}</div>`);
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
                    url: `/permissions/${id}/edit`,
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res) {
                            $('#modal').find('.modal-title').text('Edit User')
                            $('#modal').find('.modal-footer').find('button').text('Update')
                            $('#modal').modal('show')

                            $('#formModal').trigger('reset')
                            $('#formModal').find('#password').remove()

                            $('#formModal').find('input[name="_method"]').val('PUT')
                            $('#formModal').find('input[name="name"]').val(res.name)
                            $('#formModal').find('input[name="email"]').val(res.email)
                            if (res.avatar) {
                                $('.img-holder').attr('src', `/storage/${res.avatar}`)
                            } else {
                                $('.img-holder').attr('src', '/img/default_avatar.jpg')
                            }

                            $('#formModal').attr('action', `/permissions/${id}`)
                            $('#formModal')
                                .find('option')
                                .filter(function() {
                                    return $(this).val() == res.roles[0].id
                                })
                                .prop('selected', true)
                        }
                    },
                })
            })

            $(document).on('click', '.btnDelete', function() {
                const id = $(this).data('id')

                $.ajax({
                    method: 'DELETE',
                    url: "{{ route('permissions.destroy', ':id') }}".replace(':id', id),
                    data: {
                        id: id
                    },
                    success: function(res) {
                        const table = $('#permissions-table').DataTable()
                        table.ajax.reload()
                    },
                })
            })
        })
    </script>
@endpush

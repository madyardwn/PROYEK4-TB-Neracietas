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
                                        <img class="img-holder img-thumbnail" width="265" height="300" src=""
                                            alt="">
                                        <input type="file" class="form-control mt-2" id="avatar" name="avatar">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <!-- nim -->
                                    <div class="form-group mb-3">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" class="form-control" id="nim" name="nim"
                                            value="{{ old('nim') }}" placeholder="Masukkan NIM">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="na" class="form-label">Nomor Anggota</label>
                                        <input type="text" class="form-control" id="na" name="na"
                                            value="{{ old('na') }}" placeholder="Masukkan Nomor Anggota">
                                    </div>


                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Masukkan Nama">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email') }}" placeholder="Masukkan Email">
                                    </div>

                                    <!-- nama_bagus -->
                                    <div class="form-group mb-3">
                                        <label for="nama_bagus" class="form-label">Nama Bagus</label>
                                        <input type="text" class="form-control" id="nama_bagus" name="nama_bagus"
                                            value="{{ old('nama_bagus') }}" placeholder="Masukkan Nama Bagus">
                                    </div>

                                    <!--- year -->
                                    <div class="form-group mb-3">
                                        <label for="year" class="form-label">Tahun</label>
                                        <input type="text" class="form-control" id="year" name="year"
                                            value="{{ old('year') }}" placeholder="Masukkan Tahun">
                                    </div>

                                    <div class="form-group mb-3" id="pwd">
                                        <div class="row" id="password">
                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password"
                                                    name="password">
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

                                    <div class="form-group mb-3">
                                        <label for="department" class="form-label">Department</label>
                                        <select class="form-select" id="department" name="department">
                                            <option value="" selected disabled>Pilih Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="cabinet" class="form-label">Kabinet</label>
                                        <select class="form-select" id="cabinet" name="cabinet">
                                            <option value="" selected disabled>Pilih Kabinet</option>
                                            @foreach ($cabinets as $cabinet)
                                                <option value="{{ $cabinet->id }}">{{ $cabinet->name }}</option>
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
                    url: `/users/${id}/edit`,
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

                            $('#formModal').find('input[name="nim"]').val(res.nim)
                            $('#formModal').find('input[name="na"]').val(res.na)
                            $('#formModal').find('input[name="year"]').val(res.year)
                            $('#formModal').find('input[name="nama_bagus"]').val(res.nama_bagus)

                            $('#formModal').find('input[name="name"]').val(res.name)
                            $('#formModal').find('input[name="email"]').val(res.email)

                            if (res.avatar) {
                                $('.img-holder').attr('src', `/storage/${res.avatar}`)
                            }

                            $('#formModal').attr('action', `/users/${id}`)

                            $('#formModal')
                                .find('#role')
                                .find('option')
                                .filter(function() {
                                    return $(this).val() == res.roles[0].id
                                })
                                .prop('selected', true)

                            $('#formModal')
                                .find('#department')
                                .find('option')
                                .filter(function() {
                                    return $(this).val() == res.department_id
                                })
                                .prop('selected', true)

                            $('#formModal')
                                .find('#cabinet')
                                .find('option')
                                .filter(function() {
                                    return $(this).val() == res.cabinet_id
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

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
        <div class="modal" tabindex="-1">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 9v2m0 4v.01" />
                            <path
                                d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                        </svg>
                        <h3>Are you sure?</h3>
                        <div class="text-muted">Do you really want to remove 84 files? What you've done cannot be undone.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                                        Cancel
                                    </a></div>
                                <div class="col"><a href="#" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                        Submit
                                    </a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Data Anggota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="" autocomplete="off" novalidate method="POST" id="formImport">
                            @csrf
                            <div class="form-group">
                                <div class="dropzone" id="dropzone">
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                    <div class="dz-message">
                                        <div class="text text-muted">
                                            <div class="mb-3">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <h3>Drop files here or click to upload.</h3>
                                            <span class="note">(Selected files are
                                                <strong>not</strong> actually uploaded.)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn" data-bs-dismiss="modal">Tutup</a>
                    </div>
                </div>
            </div>
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
                                        value="{{ old('nim') }}" placeholder="Masukkan NIM" max="10">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="na" class="form-label">Nomor Anggota</label>
                                    <input type="text" class="form-control" id="na" name="na"
                                        value="{{ old('na') }}" placeholder="Masukkan Nomor Anggota" max="10">
                                </div>


                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Masukkan Nama" max="50">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="Masukkan Email" max="50">
                                </div>

                                <!-- nama_bagus -->
                                <div class="form-group mb-3">
                                    <label for="nama_bagus" class="form-label">Nama Bagus</label>
                                    <input type="text" class="form-control" id="nama_bagus" name="nama_bagus"
                                        value="{{ old('nama_bagus') }}" placeholder="Masukkan Nama Bagus" max="30">
                                </div>

                                <!--- year -->
                                <div class="form-group mb-3">
                                    <label for="year" class="form-label">Tahun</label>
                                    <input type="number" class="form-control" id="year" name="year"
                                        value="{{ old('year') }}" placeholder="Masukkan Tahun">
                                </div>

                                <div class="form-group mb-3" id="pwd">
                                    <div class="row" id="password">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                max="50">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label">Password
                                                Confirmation</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" max="50">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="" selected disabled>Pilih Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="department" class="form-label">Departemen</label>
                                    <select class="form-select" id="department" name="department">
                                        <option value="" selected disabled>Pilih Departemen</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}
                                            </option>
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
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#dropzone", {
            url: "{{ route('import-users.import') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            paramName: "file",
            maxFilesize: 5, // MB
            maxFiles: 1,
            acceptedFiles: ".csv",
            dictRemoveFile: "Remove",
            dictInvalidFileType: "You can't upload files of this type.",
            init: function() {
                this.on("success", function(file, response) {
                    // hide modal and refresh
                    $('#importModal').modal('hide');
                    $('#users-table').DataTable().ajax.reload();

                    // reset dropzone
                    myDropzone.removeAllFiles();

                    // show success message
                    $('.card-header').after(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        response.success +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );

                });
                this.on("error", function(file, response) {
                    $('#formImport').find('.dz-error-message').remove();
                    $('#formImport').find('#dropzone').addClass('is-invalid');
                    $('#formImport').find('#dropzone').after(
                        '<div class="invalid-feedback">' + response.error + '</div>');

                    $('.dz-file-preview').on('click', function() {
                        $(this).remove();
                        $('#formImport').find('#dropzone').removeClass('is-invalid');
                        $('#formImport').find('#dropzone').next('.invalid-feedback').remove();

                        // reset dropzone
                        myDropzone.removeAllFiles();
                    });
                });
            }
        });
    </script>

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
                                for (let i = 0; i < value.length; i++) {
                                    $('#formModal').find(`#${key}`).after(`
                                        <div class="invalid-feedback">
                                            ${value[i]}
                                        </div>
                                    `);
                                }
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

                            $('#formModal').find('#password').remove()
                            $('#formModal').trigger('reset')

                            $('#formModal').find('input[name="_method"]').val('PUT')

                            $('#formModal').find('input[name="nim"]').val(res.nim)
                            $('#formModal').find('input[name="na"]').val(res.na)
                            $('#formModal').find('input[name="year"]').val(res.year)
                            $('#formModal').find('input[name="nama_bagus"]').val(res.nama_bagus)

                            $('#formModal').find('input[name="name"]').val(res.name)
                            $('#formModal').find('input[name="email"]').val(res.email)

                            if (res.avatar != null) {
                                $('.img-holder').attr('src', `/storage/${res.avatar}`)
                            } else {
                                $('.img-holder').attr('src', '/img/default_avatar.png')
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

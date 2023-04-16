<div class="modal fade action-modal" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
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
                                <input type="file" class="form-control" id="avatar" name="avatar" id="avatar">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <!-- nim -->
                            <div class="form-group mb-3">
                                <label for="nim" class="form-label">NIM</label>
                                <input type="number" class="form-control" id="nim" name="nim"
                                    value="{{ old('nim') }}" placeholder="Masukkan NIM">
                            </div>

                            <div class="form-group mb-3">
                                <label for="na" class="form-label">Nomor Anggota</label>
                                <input type="number" class="form-control" id="na" name="na"
                                    value="{{ old('na') }}" placeholder="Masukkan Nomor Anggota">
                            </div>


                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Masukkan Nama" max="50">
                            </div>

                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="Masukkan Email" max="50"
                                    autocomplete="username">
                            </div>

                            <!-- nama_bagus -->
                            <div class="form-group mb-3">
                                <label for="nama_bagus" class="form-label">Nama Bagus</label>
                                <input type="text" class="form-control" id="nama_bagus" name="nama_bagus"
                                    value="{{ old('nama_bagus') }}" placeholder="Masukkan Nama Bagus" max="20">
                            </div>

                            <!--- year -->
                            <div class="form-group mb-3">
                                <label for="year" class="form-label">Angkatan</label>
                                <input type="number" class="form-control" id="year" name="year"
                                    value="{{ old('year') }}" placeholder="Masukkan Tahun Angkatan">
                            </div>

                            <div class="form-group mb-3 password">
                                <div class="row input-password">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            max="50" autocomplete="new-password">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Password
                                            Confirmation</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" max="50" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="" id="role" name="role">
                                    <option value="" selected disabled>Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="department" class="form-label">Departemen</label>
                                <select class="" id="department" name="department">
                                    <option value="" selected disabled>Pilih Departemen</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">
                                            ({{ $department->status == 1 ? 'Aktif' : 'Tidak Aktif' }})
                                            {{ $department->cabinet_name }} -
                                            {{ $department->name }}
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // jalankan fungsi ketika halaman sudah siap
        $(document).ready(function() {

            // inisialisasi datatable
            const table = $('#users-table');

            const tomselectDepartment = new TomSelect('#department');
            const tomselectRole = new TomSelect('#role');

            // -------------------------------------------------
            // AJAX SETUP
            // -------------------------------------------------       
            $ajaxSetup = $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // -------------------------------------------------
            //  CHECKBOX ACTION
            // ------------------------------------------------- 

            // array for store id
            const ids = [];

            // table on refresh or change page, disable button delete and uncheck all checkbox
            table.on('draw.dt', function() {
                $('#selectedDelete').prop('disabled', true);
                $('#checkAll').prop('checked', false);
            });

            // Checkbox All : check all checkbox in current page
            $(document).on('click', '#checkAll', function() {
                if ($(this).is(':checked')) {
                    ids.splice(0, ids.length);
                    $('.checkItem').prop('checked', true);
                    $('.checkItem').each(function() {
                        ids.push($(this).val());
                    });
                } else {
                    $('.checkItem').prop('checked', false);
                    ids.splice(0, ids.length);
                }

                $('#selectedDelete').prop('disabled', ids.length === 0);
            });

            // Checkbox Single : check single checkbox and push id to array
            $(document).on('click', '.checkItem', function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                    $('#checkAll').prop('checked', $('.checkItem:checked').length === $('.checkItem')
                        .length);
                } else {
                    ids.splice(ids.indexOf($(this).val()), 1);
                    $('#checkAll').prop('checked', false);
                }

                $('#selectedDelete').prop('disabled', ids.length === 0);
            });

            // Checkboxes Deletion
            $(document).on('click', '#selectedDelete', function() {

                Swal.fire({
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    title: 'Mohon Tunggu',
                    html: 'Sedang menghapus data',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    stopKeydownPropagation: false,
                })

                $.ajax({
                    url: "{{ route('users.destroy', ':id') }}".replace(':id', ids),
                    method: 'DELETE',
                    data: {
                        ids: ids
                    },
                    success: function(res) {
                        if (res) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    html: res.message,
                                    showConfirmButton: true
                                })
                                .then((result) => {
                                    if (result.isConfirmed) {
                                        table.DataTable().ajax.reload();
                                        ids.splice(0, ids.length);
                                        $('#checkAll').prop('checked', false);
                                        $('#selectedDelete').prop('disabled', true);

                                        $('#card').before(
                                            '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                            res.message +
                                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                            '</div>'
                                        );

                                        $('.alert').delay(3000).slideUp(300,
                                            function() {
                                                $(this).alert('close');
                                            });
                                    }
                                });
                        }
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: err.responseJSON.message,
                            showConfirmButton: true
                        });
                    }
                });
            });


            // -------------------------------------------------
            // IMAGE PREVIEW
            // -------------------------------------------------            
            $('input[name="avatar"]').on('change', function() {
                const file = $(this)[0].files[0]
                const reader = new FileReader()

                reader.onload = function(e) {
                    $('.img-holder').attr('src', e.target.result)
                }

                reader.readAsDataURL(file)
            });


            // -------------------------------------------------
            // SUBMIT FORM
            // -------------------------------------------------
            $('form').on('submit', function(e) {
                e.preventDefault();

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $('button[type="submit"]').attr('disabled', true);
                $('button[type="submit"]').addClass('btn-loading');

                // Inisialisasi Form
                const form = $(this);
                const formData = new FormData(form[0]);
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
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                    timerProgressBar: true,
                                })
                                .then(() => {
                                    $('button[type="submit"]').attr('disabled',
                                        false);
                                    $('button[type="submit"]').removeClass(
                                        'btn-loading');
                                    $('.action-modal').modal('hide');

                                    // Reload Datatable
                                    table.DataTable().ajax.reload();

                                    // Alert
                                    $('#card').before(
                                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                        res.message +
                                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                        '</div>'
                                    );

                                    $('.alert').delay(3000).slideUp(300,
                                        function() {
                                            $(this).alert('close');
                                        });
                                })
                        }
                    },
                    error: function(err) {
                        if (err) {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').removeClass('btn-loading');

                            $.each(err.responseJSON.errors, function(key, value) {
                                $('form').find(`#${key}`).addClass(
                                    'is-invalid');
                                for (let i = 0; i < value.length; i++) {
                                    $('form').find(`#${key}`).after(`
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


            // -------------------------------------------------
            // TAMBAH DATA
            // -------------------------------------------------
            $(document).on('click', '.btnAdd', function() {
                // Modal Setup
                resetForm();
                $('.modal-title').text('Tambah Anggota')
                $('.modal-footer').find('button').text('Simpan')

                // Image Preview
                $('.img-holder').attr('src', '/img/default_avatar.png');

                // Add Password Input
                if ($('.password').length) {
                    $('.input-password').remove();
                    $('.password').append(`
                        <div class="row input-password">
                        <div class="col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        </div>
                    `);
                }

                // Define Action
                $('form').attr("action", "{{ route('users.store') }}")

                // Assign POST Method
                $('input[name="_method"]').val('POST');

            });

            // -------------------------------------------------
            // EDIT DATA
            // -------------------------------------------------
            $(document).on('click', '.btnEdit', function() {
                // Inisialisasi Form
                const id = $(this).data('id')
                const action = $(this).data('action')

                $.ajax({
                    method: 'GET',
                    url: action.replace(':id', id),
                    success: function(res) {
                        if (res) {
                            // Modal Setup
                            resetForm();
                            $('.modal-title').text('Edit Anggota')
                            $('.modal-footer').find('button').text('Simpan')
                            $('.input-password').remove()

                            // Assign Value to Form
                            $('input[name="_method"]').val('PUT')
                            $('input[name="nim"]').val(res.nim)
                            $('input[name="na"]').val(res.na)
                            $('input[name="year"]').val(res.year)
                            $('input[name="nama_bagus"]').val(res.nama_bagus)
                            $('input[name="name"]').val(res.name)
                            $('input[name="email"]').val(res.email)

                            // Avatar Preview
                            $('.img-holder').attr('src',
                                res.avatar ?
                                `/storage/${res.avatar}` :
                                `/img/default_avatar.png`
                            )

                            // Assign Action to Form
                            $('form').attr('action',
                                "{{ route('users.update', ':id') }}"
                                .replace(':id', res.id))

                            // Assign Selected Role
                            tomselectRole.setValue(res.roles[0].id)

                            // Assign Selected Department
                            tomselectDepartment.setValue(res.department_id)
                        }
                    },
                })
            })

            // -------------------------------------------------
            // HAPUS DATA
            // -------------------------------------------------
            $(document).on('click', '.btnDelete', function() {
                const id = $(this).data('id');
                const action = $(this).data('action');

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'

                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            title: 'Loading...',
                            html: 'Sedang menghapus data...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        })

                        $.ajax({
                            method: 'DELETE',
                            url: action.replace(':id', id),
                            success: function(res) {
                                if (res) {
                                    Swal.fire(
                                        'Deleted!',
                                        res.message,
                                        'success'
                                    ).then((result) => {
                                        if (result.isConfirmed) {

                                            // Reload Datatable
                                            table.DataTable().ajax
                                                .reload();

                                            // Show Alert
                                            $('#card').before(
                                                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                                res.message +
                                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                                '</div>'
                                            );

                                            $('.alert').delay(3000)
                                                .slideUp(
                                                    300,
                                                    function() {
                                                        $(this)
                                                            .alert(
                                                                'close'
                                                            );
                                                    });
                                        }
                                    })
                                }
                            },
                            error: function(err) {
                                if (err) {
                                    Swal.fire(
                                        'Error!',
                                        err.responseJSON.message,
                                        'error'
                                    )
                                }
                            }
                        })
                    }
                })
            });
            // -------------------------------------------------
            // FUNCTION
            // -------------------------------------------------
            function resetForm() {
                tomselectDepartment.clear(true);
                tomselectRole.clear(true);
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('form')[0].reset();
                $('.action-modal').modal('show')
            }
        })
    </script>
@endpush

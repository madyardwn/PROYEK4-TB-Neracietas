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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="logo" class="form-label d-block text-center">Logo</label>
                                <img id="img-logo" class="img-thumbnail" src="" alt="">
                                <input type="file" class="form-control mt-2" id="logo" name="logo">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filosofy" class="form-label d-block text-center">Filosofi</label>
                                <div class="img-thumbnail d-flex justify-content-center align-items-center"
                                    style="height: 300px; background-image: url(''); background-repeat: no-repeat; background-size: cover; background-position: center;">
                                    <img id="img-filosofy" class="img-fluid" src="" alt="">
                                </div>
                                <input type="file" class="form-control mt-2" id="filosofy" name="filosofy">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- nim -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Kabinet</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Masukkan Nama" max="50">
                            </div>

                            <!-- description -->
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Masukkan Deskripsi" rows="3"></textarea>
                            </div>

                            <!--- year -->
                            <div class="form-group mb-3">
                                <label for="year" class="form-label">Tahun</label>
                                <input type="number" class="form-control" id="year" name="year"
                                    value="{{ old('year') }}" placeholder="Masukkan Tahun">
                            </div>

                            <div class="form-group mb-3">
                                <label for="department" class="form-label">Status</label>
                                <input type="checkbox" name="is_active" id="is_active" data-toggle="toggle"
                                    value="1" data-onlabel="<i class='fa fa-check'></i> Aktif"
                                    data-offlabel="<i class='fa fa-times p-1'></i> Tidak Aktif" data-onstyle="success"
                                    data-offstyle="secondary" data-width="120" data-height="100%">
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

@push('scripts')
    <script>
        // jalankan fungsi ketika halaman sudah siap
        $(document).ready(function() {

            // inisialisasi datatable
            const table = $('#cabinets-table');

            // ajax header csrf token
            $ajaxSetup = $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

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
                    url: "{{ route('cabinets.destroy', ':id') }}".replace(':id', ids),
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
            $('input[name="logo"]').on('change', function() {
                const file = $(this)[0].files[0]
                const reader = new FileReader()

                reader.onload = function(e) {
                    $('#img-logo').attr('src', e.target.result)
                }

                // if empty file
                if (file == undefined) {
                    $('#img-logo').attr('src', "{{ asset('assets/img/default_avatar.png') }}");
                }

                reader.readAsDataURL(file)
            });

            $('input[name="filosofy"]').on('change', function() {
                const file = $(this)[0].files[0]
                const reader = new FileReader()

                reader.onload = function(e) {
                    $('#img-filosofy').attr('src', e.target.result)
                }

                // if empty file
                if (file == undefined) {
                    $('#img-filosofy').attr('src', "{{ asset('assets/img/philosophy.jpg') }}");
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
                                    timer: 1500
                                })
                                .then(() => {
                                    $('button[type="submit"]').attr('disabled', false);
                                    $('button[type="submit"]').removeClass('btn-loading');
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

                                    $('.alert').delay(3000).slideUp(300, function() {
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
                                $('form').find(`#${key}`).addClass('is-invalid');
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
                $('.modal-title').text('Buat Kabinet Baru');
                $('.modal-footer').find('button').text('Simpan')

                // Image Preview
                $('#img-logo').attr('src', '/img/default_avatar.png');
                $('#img-filosofy').attr('src', '/img/philosophy.jpg');

                // Define Action
                $('form').attr('action', '{{ route('cabinets.store') }}');

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
                            $('.modal-title').text('Edit Kabinet');
                            $('.modal-footer').find('button').text('Simpan')
                            $('.input-password').remove()

                            // Assign Value to Form
                            $('input[name="_method"]').val('PUT')
                            $('input[name="name"]').val(res.name)
                            $('textarea[name="description"]').val(res.description)
                            $('input[name="year"]').val(res.year)
                            if (res.is_active == 1) {
                                $('input[name="is_active"]')
                                    .prop('checked', true)
                                    .parent().removeClass('off')
                            } else {
                                $('input[name="is_active"]')
                                    .prop('checked', false)
                                    .parent().addClass('off')
                            }


                            // Avatar Preview
                            $('#img-logo').attr('src',
                                res.logo ?
                                `/storage/${res.logo}` : `/img/default_avatar.png`
                            )

                            // Filosofy Preview
                            $('#img-filosofy').attr('src',
                                res.filosofy ?
                                `/storage/${res.filosofy}` : `/img/philosophy.jpg`
                            )

                            // Assign Action to Form
                            $('form').attr('action', "{{ route('cabinets.update', ':id') }}"
                                .replace(':id', id))
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
                        $.ajax({
                            method: 'DELETE',
                            url: action.replace(':id', id),
                            success: function(res) {
                                if (res) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Data berhasil dihapus.',
                                        'success'
                                    ).then((result) => {
                                        if (result.isConfirmed) {

                                            // Reload Datatable
                                            table.DataTable().ajax.reload();

                                            // Show Alert
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
                                    })
                                }
                            },
                            error: function(err) {
                                if (err) {
                                    Swal.fire(
                                        'Gagal!',
                                        err.responseJSON.message,
                                        'error'
                                    )
                                }
                            }
                        })
                    }
                })
            });
        })

        // -------------------------------------------------
        // FUNCTION
        // -------------------------------------------------
        function resetForm() {
            $('input[name="is_active"]')
                .prop('checked', false)
                .parent().addClass('off');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('form')[0].reset();
            $('.action-modal').modal('show')
        }
    </script>
@endpush

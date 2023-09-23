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
                                <img class="img-holder img-thumbnail" width="265" height="300" src=""
                                    alt="">
                                <input type="file" class="form-control mt-2" id="logo" name="logo">
                            </div>
                        </div>  
                        <div class="col-md-9">
                            <div class="form-group mb-3">
                                <label for="label" class="form-label">Filosofy</label>
                                <textarea class="form-control" id="label" name="label" rows="3"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="department" class="form-label">Kabinet</label>
                                <select class="" id="cabinet" name="cabinet">
                                    <option value="" selected disabled>Pilih Kabinet</option>
                                    @foreach ($cabinets as $cabinet)
                                        <option value="{{ $cabinet->id }}">{{ $cabinet->name }}
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

@push('scripts')
    <script>
        // jalankan fungsi ketika halaman sudah siap
        $(document).ready(function() {

            // inisialisasi datatable
            const table = $('#filosofy-table');

            const tomselectCabinet = new TomSelect('#cabinet');

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
                    url: "{{ route('filosofy.destroy', ':id') }}".replace(':id', ids),
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
                $('.modal-title').text('Tambah Filosofy')
                $('.modal-footer').find('button').text('Simpan')

                // Image Preview
                $('.img-holder').attr('src', '/img/default_avatar.png');

                // Define Action
                $('form').attr("action", "{{ route('filosofy.store') }}")

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
                            $('.modal-title').text('Edit Filosofy')
                            $('.modal-footer').find('button').text('Simpan')
                            $('.input-password').remove()

                            // Assign Value to Form
                            $('input[name="_method"]').val('PUT')
                            $('textarea[name="label"]').val(res.label)
                            $('input[name="cabinet"]').val(res.cabinet)

                            // Avatar Preview
                            $('.img-holder').attr('src',
                                res.logo ?
                                `/storage/${res.logo}` :
                                `/img/default_avatar.png`
                            )

                            // Assign Action to Form
                            $('form').attr('action',
                                "{{ route('filosofy.update', ':id') }}"
                                .replace(':id', res.id))
                            tomselectCabinet.setValue(res.cabinet_id)
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
                tomselectCabinet.clear(true);
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('form')[0].reset();
                $('.action-modal').modal('show')
            }
        })
    </script>
@endpush

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
                                <label for="poster" class="form-label">Poster</label>
                                <img class="img-holder img-thumbnail" width="265" height="300" src=""
                                    alt="">
                                <input type="file" class="form-control" id="poster" name="poster"
                                    value="{{ old('poster') }}" placeholder="Masukkan Poster">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <!-- nim -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Event</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Masukkan Nama" max="50">
                            </div>

                            <!-- description -->
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Masukkan Deskripsi" rows="3"></textarea>
                            </div>

                            <!-- location -->
                            <div class="form-group mb-3">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    value="{{ old('location') }}" placeholder="Masukkan Lokasi" max="50">
                            </div>

                            <!-- date -->
                            <div class="form-group mb-3">
                                <label for="date" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="{{ old('date') }}" placeholder="Masukkan Tanggal">
                            </div>

                            <!-- time -->
                            <div class="form-group mb-3">
                                <label for="time" class="form-label">Waktu</label>
                                <input type="time" class="form-control" id="time" name="time"
                                    value="{{ old('time') }}" placeholder="Masukkan Waktu">
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
            const table = $('#events-table');

            // ajax header csrf token
            $ajaxSetup = $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            // Image preview
            $('input[name="poster"]').on('change', function() {
                const file = $(this)[0].files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    $('.img-holder').attr('src', e.target.result);
                }

                reader.readAsDataURL(file);
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
                $('.modal-title').text('Buat Event')
                $('.modal-footer').find('button').text('Simpan')

                // Image Preview
                $('.img-holder').attr('src', '/img/default_avatar.png');

                // Define Action
                $('form').attr('action', "{{ route('events.store') }}");

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
                            $('.modal-title').text('Edit Event')
                            $('.modal-footer').find('button').text('Simpan')
                            $('.input-password').remove()

                            // Assign Value to Form
                            $('input[name="_method"]').val('PUT')
                            $('input[name="name"]').val(res.name)
                            $('textarea[name="description"]').val(res.description)
                            $('input[name="date"]').val(res.date)
                            $('input[name="time"]').val(res.time)
                            $('input[name="location"]').val(res.location)

                            // Avatar Preview
                            $('.img-holder').attr('src',
                                res.poster ?
                                `/storage/${res.poster}` : ``
                            )

                            // Assign Action to Form
                            $('form').attr('action', "{{ route('events.update', ':id') }}"
                                .replace(':id', res.id))
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
                                        res.message,
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
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('form')[0].reset();
            $('.action-modal').modal('show')
        }
    </script>
@endpush

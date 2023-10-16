<div class="modal fade import-modal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">

                <div class="modal-header">
                    <h5 class="modal-title">Import Data Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="dropzone">
                                    <div class="fallback">
                                        <input name="file" type="file" multiple />
                                    </div>
                                    <div class="dz-message">
                                        <div class="text text-muted">
                                            <div class="mb-3">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <h3>Drop file disini atau klik untuk upload</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="import-info">
                                    <p><strong>File yang diizinkan: </strong> .csv, .xlsx</p>
                                    <p><strong>Ukuran maksimal file: </strong> 5 MB</p>
                                    <!-- attribute pada file sebagai berikut: -->
                                    <p><strong>Kolom: </strong> <br>nim, nama, email, password, tahun, nama_bagus, na, department_name, avatar, role</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <a href="#" class="btn" data-bs-dismiss="modal">Tutup</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btnImport', function() {
                $('.import-modal').modal('show');
            });

            Dropzone.autoDiscover = false;
            const myDropzone = new Dropzone(".dropzone", {
                url: "{{ route('import-users.import') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                paramName: "file",
                maxFilesize: 5, // MB
                maxFiles: 1,
                acceptedFiles: ".csv" || ".xlsx",
                dictRemoveFile: "Remove",
                dictInvalidFileType: "Kesalahan: Tipe file tidak didukung",
                init: function() {
                    this.on("success", function(file, response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // hide modal and refresh
                                $('.import-modal').modal('hide');
                                $('#users-table').DataTable().ajax.reload();

                                // reset dropzone
                                myDropzone.removeAllFiles();

                                // show success message
                                $('#card').before(
                                    '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                    response.success +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                    '</div>'
                                );

                                // remove success message
                                $('.alert-success').delay(5000).slideUp(300,
                                    function() {
                                        $(this).alert('close');
                                    });
                            }
                        });
                    });
                    this.on("error", function(file, response) {
                        $('form').find('.dz-error-message').remove();
                        $('form').find('.import-info').addClass('is-invalid');
                        $('form').find('.import-info').after(
                            '<div class="invalid-feedback">' + response.error + '</div>');

                        $('.dz-file-preview').on('click', function() {
                            $(this).remove();
                            $('form').find('.import-info').removeClass(
                                'is-invalid');
                            $('form').find('.import-info').next('.invalid-feedback')
                                .remove();

                            // reset dropzone
                            myDropzone.removeAllFiles();
                        });
                    });
                }
            });

        });
    </script>
@endpush

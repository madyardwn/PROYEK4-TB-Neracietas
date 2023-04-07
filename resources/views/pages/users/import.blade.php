<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
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


@push('scripts')
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
                    $('#card').before(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        response.success +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );

                    // remove alert after 5 seconds
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 5000);

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
@endpush

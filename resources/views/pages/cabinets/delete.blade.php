<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Are you sure?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('cabinets.destroy', ':id') }}" method="DELETE" id="deleteForm">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            $ajaxSetup = $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.btnDelete', function() {
                $('#deleteModal').modal('show');

                const id = $(this).data('id');

                $('#deleteForm').on('submit', function(e) {
                    e.preventDefault();

                    const url = $(this).attr('action').replace(':id', id);
                    const method = $(this).attr('method');

                    $.ajax({
                        url: url,
                        method: method,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#deleteModal').modal('hide');
                            $('#deleteForm')[0].reset();
                            $('#cabinets-table').DataTable().ajax.reload();

                            $('#card').before(
                                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                res.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                '</div>'
                            );

                            setTimeout(function() {
                                $('.alert').alert('close');
                            }, 5000);
                        },
                        error: function(error) {
                            $('#deleteModal').modal('hide');
                            $('#deleteForm')[0].reset();
                            $('#cabinets-table').DataTable().ajax.reload();

                            $('#card').before(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                error.responseJSON.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                '</div>'
                            );

                            setTimeout(function() {
                                $('.alert').alert('close');
                            }, 5000);
                        }
                    });
                });
            })
        });
    </script>
@endpush

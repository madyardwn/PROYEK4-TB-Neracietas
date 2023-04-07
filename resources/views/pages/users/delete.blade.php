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
                <button type="button" class="btn btn-danger" id="btnDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).on('click', '.btnDelete', function() {
            const id = $(this).data('id')

            $('#deleteModal').modal('show')

            $('#deleteModal').find('#btnDelete').on('click', function() {
                $.ajax({
                    method: 'DELETE',
                    url: `/users/${id}`,
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res) {
                            $('#deleteModal').modal('hide')
                            $('#users-table').DataTable().ajax.reload()

                            $('#card').before(
                                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                res.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                '</div>'
                            );

                            setTimeout(function() {
                                $('.alert').alert('close');
                            }, 5000);
                        }
                    }
                })
            })


        })
    </script>
@endpush

@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">Manage Users</div>
        <div class="card-body">
            {!! $dataTable->table() !!}
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
</div>
@endsection
@push('scripts')
{!! $dataTable->scripts() !!}

<script>
    const modal = new bootstrap.Modal(document.getElementById('modalAction'))

    // btn-show
    $('#users-table').on('click', '.btn-show', function() {
        $.ajax({
            method: "get",
            url: "{{ route('users.show', ':id') }}".replace(':id', $(this).data('id')),
            success: function(res) {
                $("#modalAction").find(".modal-dialog").html(res)
                modal.show()
            }
        })
    })

    // btn-edit
    $('#users-table').on('click', '.btn-edit', function() {
        $.ajax({
            method: "get",
            url: "{{ route('users.edit', ':id') }}".replace(':id', $(this).data('id')),
            success: function(res) {
                $("#modalAction").find(".modal-dialog").html(res)
                modal.show()
                store()
            }
        })
    })

    // btn-delete
    $('#users-table').on('click', '.btn-delete', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "delete",
                    url: "{{ route('users.destroy', ':id') }}".replace(':id', $(this).data('id')),
                    success: function(res) {
                        $('#dataTableBuilder').DataTable().ajax.reload()
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            }
        })
    })

    function store() {
        $('#formAction').on('submit', function(e) {
            e.preventDefault()
            $.ajax({
                method: 'post',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(res) {
                    modal.hide()
                    $('#dataTableBuilder').DataTable().ajax.reload()
                }
            })
        })
    }
</script>
@endpush

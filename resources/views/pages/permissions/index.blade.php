@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
        <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="formModal" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- role -->
                                <div class="form-group mb-3">
                                    <label for="role" class="form-label">Nama Role</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Masukkan Nama" max="50">
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
        $(document).ready(function() {

            $ajaxSetup = $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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

                const table = $('#permissions-table').DataTable();
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
                    url: `/permissions/${id}/edit`,
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res) {
                            $('#modal').find('.modal-title').text('Edit Permission')
                            $('#modal').find('.modal-footer').find('button').text('Update')
                            $('#modal').modal('show')

                            $('#formModal').find('input[name="_method"]').val('PUT')

                            $('#formModal').find('input[name="name"]').val(res.name)

                            $('#formModal').attr('action', `/roles/${id}`)
                        }
                    },
                })
            })

            $(document).on('click', '.btnDelete', function() {
                const id = $(this).data('id')

                $.ajax({
                    method: 'DELETE',
                    url: `/permissions/${id}`,
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res) {
                            const table = $('#permissions-table').DataTable()
                            table.ajax.reload()
                        }
                    },
                })
            })
        })
    </script>
@endpush

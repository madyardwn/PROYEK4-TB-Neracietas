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
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="logo" class="form-label">Poster</label>
                                        <img class="img-holder img-thumbnail" width="265" height="300" src=""
                                            alt="">
                                        <input type="file" class="form-control mt-2" id="image" name="image">
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

                                    <!-- cabinet -->
                                    <div class="form-group mb-3">
                                        <label for="cabinet" class="form-label">Kabinet</label>
                                        <select class="form-select" id="cabinet" name="cabinet">
                                            <option value="" selected disabled>Pilih Kabinet</option>
                                            @foreach ($cabinets as $cabinet)
                                                <option value="{{ $cabinet->id }}">{{ $cabinet->name }}</option>
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

            $('#image').change(function() {
                const file = $(this)[0].files[0]
                const reader = new FileReader()

                reader.onload = function(e) {
                    $('.img-holder').attr('src', e.target.result)
                }

                reader.readAsDataURL(file)
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

                const table = $('#events-table').DataTable();
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
                    url: `/events/${id}/edit`,
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res) {
                            $('#modal').find('.modal-title').text('Edit Event')
                            $('#modal').find('.modal-footer').find('button').text('Update')
                            $('#modal').modal('show')

                            $('#formModal').find('input[name="_method"]').val('PUT')

                            $('#formModal').find('input[name="name"]').val(res.name)
                            $('#formModal').find('textarea[name="description"]').val(res
                                .description)

                            console.log(res.image)
                            if (res.image != null) {
                                $('.img-holder').attr('src', `/storage/${res.image}`)
                            } else {
                                $('.img-holder').attr('src', '/img/default_avatar.png')
                            }

                            $('#formModal').find('input[name="location"]').val(res.location)
                            $('#formModal').find('input[name="date"]').val(res.date)
                            $('#formModal').find('input[name="time"]').val(res.time)
                            $('#formModal').find('select[name="cabinet"]').val(res.cabinet_id)

                            $('#formModal').attr('action', `/events/${res.id}`)
                        }
                    },
                })
            })

            $(document).on('click', '.btnDelete', function() {
                const id = $(this).data('id')

                $.ajax({
                    method: 'DELETE',
                    url: `/events/${id}`,
                    data: {
                        id: id
                    },
                    success: function(res) {
                        if (res) {
                            const table = $('#events-table').DataTable()
                            table.ajax.reload()
                        }
                    },
                })
            })
        })
    </script>
@endpush

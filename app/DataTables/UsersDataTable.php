<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model
            ->newQuery()
            ->select(['users.name', 'users.email', 'users.avatar', 'users.id', 'roles.name as roles', 'roles.id as role_id'])
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([
                Button::make('add')->action('function() {
                    if ($("#formModal").find("#password").length) {
                        $("#formModal").find("#password").remove();
                    }
                    $(".is-invalid").removeClass("is-invalid")
                    $(".invalid-feedback").remove()
                    $("#formModal").find("#pwd").append(`
                        <div class="row" id="password">
                            <div class="col-md-6">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    `)
                    $("#formModal").trigger("reset");
                    $(".img-holder").attr("src", "/img/default_avatar.jpg");
                    $("#modal").find(".modal-title").text("Tambah User");
                    $("#modal").find(".modal-footer").find("button").text("Simpan");


                    $("#modal").modal("show");


                    $("#formModal").attr("action", "' . route('users.store') . '");
                    $("#formModal").find(' . "'input[name=_method]'" . ').val("POST");
                }'),
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')
                ->width(20)
                ->addClass('text-center')
                ->title('ID'),
            Column::make('avatar')
                ->width(60)
                ->title('Avatar')
                ->render('function() {
                    return `
                        <img src="/storage/${this.avatar}" class="img-fluid img-thumbnail" width="100">
                    `
                }'),
            Column::make('name'),
            Column::make('email'),
            Column::make('roles.name')
                ->width(60)
                ->addClass('text-center')
                ->title('Role')
                ->render('function() {
                    if (this.roles == "admin") {
                        return `<span class="badge bg-danger">${this.roles}</span>`
                    } else if (this.roles == "user") {
                        return `<span class="badge bg-success">${this.roles}</span>`
                    } else {
                        return ``
                    }
                }'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Action')
                ->render('function() {
                    return `
                        <button type="button" class="btn btn-ghost-primary  btn-sm btnEdit fa fa-edit" data-id="${this.id}"></button>
                        <button type="button" class="btn btn-ghost-danger  btn-sm btnDelete fa fa-trash" data-id="${this.id}"></button>
                    `
                }')
                ->width(50),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('Y-m-d_H-i-s');
    }
}

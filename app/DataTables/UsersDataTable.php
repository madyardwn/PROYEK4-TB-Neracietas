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
        return $model->newQuery()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name', 'users.email', 'roles.name as role_name', 'roles.id as role_id')
            ->orderBy('role_name', 'asc');
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
                    $("#modal").find(".modal-title").text("Tambah User");
                    $("#modal").find(".modal-footer").find("button").text("Simpan");
                    $("#modal").modal("show");
                    $("#formModal").find("img").attr("src", "img/himakom.png");

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
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('role_name')->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('Action')->width(100)
                ->render('function() {
                    return `
                            <button type="button" class="btn btn-sm btn-primary btnEdit fa fa-edit" data-id="${this.id}"></button>
                            <button type="button" class="btn btn-sm btn-danger btnDelete fa fa-trash" data-id="${this.id}"></button>
                    `
                }'),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('Y-m-d_H-i-s');
    }
}

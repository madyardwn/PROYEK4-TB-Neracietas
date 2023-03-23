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
        return $model->newQuery();
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
                    $.ajax({
                        method: "get",
                        url: "' . route('users.create') . '",
                        success: function(res) {
                            $("#modalAction").find(".modal-dialog").html(res)
                            modal.show()
                            store()
                        }
                    })
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
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('Action')->width(100)
                ->render('function() {
                    return `
                            <button type="button" class="btn btn-sm btn-info btn-show fa fa-eye" data-id="${this.id}"></button>
                            <button type="button" class="btn btn-sm btn-primary btn-edit fa fa-edit" data-id="${this.id}"></button>
                            <button type="button" class="btn btn-sm btn-danger btn-delete fa fa-trash" data-id="${this.id}"></button>
                    `
                }'),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}

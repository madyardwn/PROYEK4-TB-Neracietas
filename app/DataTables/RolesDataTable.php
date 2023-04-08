<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    private $no = 1;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

        return (new EloquentDataTable($query))
            ->addColumn('No', function ($role) {
                return $this->no++;
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('roles-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([
                Button::make('')
                    ->text('<span class="fa fa-plus"></span>&nbsp; Tambah')
                    ->addClass('btnAdd'),
                Button::make('export')
                    ->text('<span class="fa fa-download"></span>&nbsp; Export')
                    ->titleAttr('Export'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('No')
                ->title('No')
                ->searchable(false)
                ->orderable(false)
                ->width(30),
            Column::make('name'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Action')
                ->render('function() {
                return `
                    <a class="btnEdit btn btn-ghost-primary  btn-sm fa fa-edit" data-action="' . route('roles.edit', ':id') . '" data-id="${this.id}"></a>
                    <a class="btnDelete btn btn-ghost-danger btn-sm fa fa-trash" data-action="' . route('roles.destroy', ':id') . '" data-id="${this.id}"></a>
                `
                }')
                ->width(50),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Roles_' . date('YmdHis');
    }
}

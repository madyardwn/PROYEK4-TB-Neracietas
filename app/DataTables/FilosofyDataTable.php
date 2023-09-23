<?php

namespace App\DataTables;

use App\Models\Filosofy;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FilosofyDataTable extends DataTable
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
            ->addColumn(
                'No',
                function ($cabinet) {
                    return $this->no++;
                }
            );
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Filosofy $model): QueryBuilder
    {
        return $model
            ->select(
                [
                    'cabinets.id as cabinet_id',
                    'cabinets.name as name',
                    'filosofy.id',
                    'filosofy.label',
                    'filosofy.logo',             
                ]
            )
            ->leftJoin('cabinets', 'cabinets.id', '=', 'filosofy.cabinet_id');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('filosofy-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // order by year
            ->orderBy(4)
            ->buttons(
                [
                    Button::make('')
                        ->text('<span class="fa fa-plus"></span>&nbsp; Tambah')
                        ->addClass('btnAdd'),
                    Button::make('export')
                        ->text('<span class="fa fa-download"></span>&nbsp; Export')
                        ->titleAttr('Export'),
                    Button::make('reload'),
                    Button::make('')
                        ->text('<span class="fa fa-trash"></span>&nbsp; Hapus')
                        ->attr(
                            [
                                'id' => 'selectedDelete',
                                'disabled' => 'disabled'
                            ]
                        )
                ]
            );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('checkbox')
                ->width(10)
                ->addClass('text-center')
                ->sortable(false)
                ->searchable(false)
                ->title(
                    '<input type="checkbox" class="form-check-input" id="checkAll">'
                )
                ->render(
                    'function() {
                    return `<input type="checkbox" class="form-check-input checkItem" name="id[]" value="${this.id}" data-id="${this.id}">`;
            }'
                ),
            Column::make('No')->title('No')->searchable(false)->orderable(false)
                ->render(
                    'function() {
                    return this.No;
                }'
                )
                ->width(10),
            Column::make('name')->title('Nama Kabinet'),
            Column::make('filosofy.logo')->title('Logo')->render(
                'function() {
                return `<img src="/storage/${this.logo}" class="img-fluid" width="100px">`;
            }'
            )
                ->addClass('text-center'),
            Column::make('filosofy.label')->title('Label')
                ->render(
                    'function() {
                return `${this.label}`;
            }'
                )
                ->width(500),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Action')
                ->render(
                    'function() {
                return `
                    <a class="btnEdit btn btn-ghost-primary  btn-sm fa fa-edit" data-action="' . route('filosofy.edit', ':id') . '" data-id="${this.id}"></a>
                    <a class="btnDelete btn btn-ghost-danger btn-sm fa fa-trash" data-action="' . route('filosofy.destroy', ':id') . '" data-id="${this.id}"></a>
                `
                }'
                )
                ->width(50),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Filosofy_' . date('YmdHis');
    }
}

<?php

namespace App\DataTables;

use App\Models\Cabinet;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class CabinetsDataTable extends DataTable
{
    private $no = 1;
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('No', function ($cabinet) {
                return $this->no++;
            });
    }

    public function query(Cabinet $model): QueryBuilder
    {
        return $model
            ->select([
                'cabinets.id',
                'cabinets.name',
                'cabinets.logo',
                'cabinets.year',
                'cabinets.description',
                'cabinets.is_active',
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('cabinets-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // order by year
            ->orderBy(4)
            ->buttons([
                Button::make('')
                    ->text('<span class="fa fa-plus"></span>&nbsp; Tambah')
                    ->addClass('btnAdd'),
                Button::make('export')
                    ->text('<span class="fa fa-download"></span>&nbsp; Export')
                    ->titleAttr('Export'),
                Button::make('reload'),
                Button::make('')
                    ->text('<span class="fa fa-trash"></span>&nbsp; Hapus')
                    ->attr([
                        'id' => 'selectedDelete',
                        'disabled' => 'disabled'
                    ])
            ]);
    }

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
                ->render('function() {
                    return `<input type="checkbox" class="form-check-input checkItem" name="id[]" value="${this.id}" data-id="${this.id}">`;
            }'),
            Column::make('No')->title('No')->searchable(false)->orderable(false)
                ->render('function() {
                    return this.No;
                }')
                ->width(10),
            Column::make('name')->title('Nama Kabinet')
                ->width(100),
            Column::make('logo')->title('Logo')->render('function() {
                return `<img src="/storage/${this.logo}" class="img-fluid" width="100px">`;
            }')
                ->width(100)
                ->addClass('text-center'),
            Column::make('year')->title('Tahun')
                ->width(50)
                ->addClass('text-center'),
            Column::make('is_active')
                ->title('Status')
                ->render('function() {
                    if (this.is_active == 1) {
                        return `<span class="badge bg-success">Aktif</span>`
                    } else {
                        return `<span class="badge bg-danger">Tidak Aktif</span>`
                    }
            }')
                ->width(50)
                ->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Action')
                ->render('function() {
                return `
                    <a class="btnEdit btn btn-ghost-primary  btn-sm fa fa-edit" data-action="' . route('cabinets.edit', ':id') . '" data-id="${this.id}"></a>
                    <a class="btnDelete btn btn-ghost-danger btn-sm fa fa-trash" data-action="' . route('cabinets.destroy', ':id') . '" data-id="${this.id}"></a>
                `
                }')
                ->width(50),
        ];
    }

    protected function filename(): string
    {
        return 'Cabinets_' . date('YmdHis');
    }
}

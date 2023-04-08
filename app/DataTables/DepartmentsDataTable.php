<?php

namespace App\DataTables;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DepartmentsDataTable extends DataTable
{
    private $no = 1;
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('No', function ($departmen) {
                return $this->no++;
            });
    }

    public function query(Department $model): QueryBuilder
    {
        return $model
            ->select([
                'departments.id',
                'departments.name',
                'departments.logo',
                'departments.description',
                'cabinets.name as cabinet_name',
                'cabinets.is_active as status',
                'cabinets.year as year'
            ])
            ->leftJoin('cabinets', 'cabinets.id', '=', 'departments.cabinet_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('departments-table')
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

    public function getColumns(): array
    {
        return [
            Column::make('No')->title('No')->searchable(false)->orderable(false)
                ->render('function() {
                    return this.No;
                }')
                ->width(10)
                ->addClass('text-center'),
            Column::make('name')->title('Nama Departemen')
                ->width(200),
            Column::make('logo')->title('Logo')->render('function() {
                return `<img src="/storage/${this.logo}" class="img-fluid" width="50px">`;
            }')
                ->width(50)
                ->addClass('text-center'),
            Column::make('cabinets.name')->title('Kabinet')->render('function() {
                return this.cabinet_name;
            }')
                ->width(100),
            Column::make('cabinets.year')->title('Tahun')->render('function() {
                return this.year;
            }')
                ->width(50)
                ->addClass('text-center'),
            Column::make('cabinets.is_active')->title('Status')->render('function() {
                    if (this.status == 1) {
                        return `<span class="badge bg-success">Active</span>`
                    } else {
                        return `<span class="badge bg-danger">Inactive</span>`
                    }
                }')
                ->width(50)
                ->addClass('text-center'),
            Column::computed('Opsi')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Action')
                ->render('function() {
                return `
                    <a class="btnEdit btn btn-ghost-primary  btn-sm fa fa-edit" data-action="' . route('departments.edit', ':id') . '" data-id="${this.id}"></a>
                    <a class="btnDelete btn btn-ghost-danger btn-sm fa fa-trash" data-action="' . route('departments.destroy', ':id') . '" data-id="${this.id}"></a>
                `
                }')
                ->width(50),
        ];
    }

    protected function filename(): string
    {
        return 'Departmens_' . date('YmdHis');
    }
}

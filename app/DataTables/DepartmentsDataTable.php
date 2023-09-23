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
                'departments.short_name',
                'departments.logo',
                'departments.description',
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('departments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // order by year
            ->orderBy(5, 'desc')
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
                ->width(10)
                ->addClass('text-center'),
            Column::make('name')->title('Nama Departemen')
                ->width(200),
            Column::make('short_name')->title('Singkatan')
                ->width(100),
            Column::make('logo')->title('Logo')->render('function() {
                if (this.logo != null) {
                    return `<img src="' . asset('storage') . '/${this.logo}" width="50" height="50" />`
                } else {
                    return ``;
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

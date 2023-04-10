<?php

namespace App\DataTables;

use App\Models\Program;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProgramsDataTable extends DataTable
{
    private $no = 1;
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('No', function ($program) {
                return $this->no++;
            });
    }

    public function query(Program $model): QueryBuilder
    {
        return $model
            ->select([
                'programs.id',
                'programs.name',
                'programs.description',
                'programs.progress',
                'departments.name as department_name',
            ])
            ->leftJoin('departments', 'departments.id', '=', 'programs.department_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('programs-table')
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
            Column::make('No')->title('No')->searchable(false)->orderable(false),
            Column::make('name')->title('Program Kerja'),
            Column::make('description')->title('Deskripsi'),
            Column::make('departments.name')->title('Derpartemen')->render('function() {
                return this.department_name;
            }'),
            Column::make('progress')->title('Proses')->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Opsi')
                ->render('function() {
                return `
                    <a class="btnEdit btn btn-ghost-primary  btn-sm fa fa-edit" data-action="' . route('programs.edit', ':id') . '" data-id="${this.id}"></a>
                    <a class="btnDelete btn btn-ghost-danger btn-sm fa fa-trash" data-action="' . route('programs.destroy', ':id') . '" data-id="${this.id}"></a>
                `
                }')
                ->width(50),
        ];
    }

    protected function filename(): string
    {
        return 'Programs_' . date('YmdHis');
    }
}

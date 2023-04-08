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
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('No')->title('No')->searchable(false)->orderable(false),
            Column::make('name')->title('Name'),
            Column::make('description')->title('Description'),
            Column::make('departments.name')->title('Department')->render('function() {
                return this.department_name;
            }'),
            Column::make('progress')->title('Progress')->searchable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Action')
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

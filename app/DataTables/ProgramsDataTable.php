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
                Button::make('add')->action('function() {                    
                    $(".is-invalid").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $("#formModal").trigger("reset");                    
                    $("#modal").find(".modal-title").text("Buat Program");
                    $("#modal").find(".modal-footer").find("button").text("Simpan");
                    $("#modal").modal("show");
                    $("#formModal").attr("action", "' . route('programs.store') . '");
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
                    <button type="button" class="btn btn-ghost-primary  btn-sm btnEdit fa fa-edit" data-id="${this.id}"></button>
                    <button type="button" class="btn btn-ghost-danger  btn-sm btnDelete fa fa-trash" data-id="${this.id}"></button>
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

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
                'cabinets.name as cabinet_name'
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
                Button::make('add')->action('function() {                    
                    $(".is-invalid").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $("#formModal").trigger("reset");
                    $(".img-holder").attr("src", "/img/default_avatar.png");
                    $("#modal").find(".modal-title").text("Buat Departemen");
                    $("#modal").find(".modal-footer").find("button").text("Simpan");
                    $("#modal").modal("show");
                    $("#formModal").attr("action", "' . route('departments.store') . '");
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
            Column::make('logo')->title('Logo')->render('function() {
                return `<img src="/storage/${this.logo}" class="img-fluid" width="100px">`;
            }'),
            Column::make('description')->title('Description'),
            Column::make('cabinets.name')->title('Cabinet')->render('function() {
                return this.cabinet_name;
            }'),
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
        return 'Departmens_' . date('YmdHis');
    }
}

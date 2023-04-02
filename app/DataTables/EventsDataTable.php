<?php

namespace App\DataTables;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EventsDataTable extends DataTable
{
    private $no = 1;
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('No', function ($event) {
                return $this->no++;
            });
    }

    public function query(Event $model): QueryBuilder
    {
        return $model
            ->select([
                'events.id',
                'events.name',
                'events.description',
                'events.date',
                'events.time',
                'events.location',
                'events.image',
                'events.is_active',
                'cabinets.name as cabinet',
            ])
            ->join('cabinets', 'cabinets.id', '=', 'events.cabinet_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('events-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([
                Button::make('add')->action('function() {                    
                    $(".is-invalid").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $("#formModal").trigger("reset");
                    $(".img-holder").attr("src", "/img/default_avatar.png");
                    $("#modal").find(".modal-title").text("Buat Event");
                    $("#modal").find(".modal-footer").find("button").text("Simpan");
                    $("#modal").modal("show");
                    $("#formModal").attr("action", "' . route('events.store') . '");
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
            Column::make('image')->title('Image')->render('function() {
                return `<img src="/storage/${this.image}" class="img-fluid" width="100px">`;
            }'),
            Column::make('description')->title('Description'),
            Column::make('location')->title('Location'),
            Column::make('date')->title('Date'),
            Column::make('time')->title('Time'),
            Column::make('is_active')->title('Is Active')->render('function() {
                return this.is_active == 1 ? "Active" : "Inactive";
            }'),
            Column::make('cabinets.name')->title('Cabinet')->render('function() {
                return this.cabinet;
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
        return 'Events_' . date('YmdHis');
    }
}

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
                'events.poster',
                'events.is_active',
            ]);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('events-table')
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
            Column::make('poster')->title('Poster')->render('function() {
                return `<img src="/storage/${this.poster}" class="img-fluid" width="100px">`;
            }'),
            Column::make('description')->title('Description'),
            Column::make('location')->title('Location'),
            Column::make('date')->title('Date'),
            Column::make('time')->title('Time'),
            Column::make('is_active')->title('Is Active')->render('function() {
                return this.is_active == 1 ? "Active" : "Inactive";
            }'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Action')
                ->render('function() {
                return `
                    <a class="btnEdit btn btn-ghost-primary  btn-sm fa fa-edit" data-action="' . route('events.edit', ':id') . '" data-id="${this.id}"></a>
                    <a class="btnDelete btn btn-ghost-danger btn-sm fa fa-trash" data-action="' . route('events.destroy', ':id') . '" data-id="${this.id}"></a>
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

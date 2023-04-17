<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class UsersDataTable extends DataTable
{
    private $no = 1;
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('No', function ($user) {
                return $this->no++;
            });
    }

    public function query(User $model): QueryBuilder
    {
        return $model
            ->select([
                'users.id',
                'users.nim',
                'users.name',
                'users.email',
                'users.avatar',
                'users.year',
                'roles.name as roles',
                'departments.name as departments',
                'users_departments.is_active as status'
            ])
            // leftjoin with department
            ->leftJoin('users_departments', 'users.id', '=', 'users_departments.user_id')
            ->leftJoin('departments', 'users_departments.department_id', '=', 'departments.id')
            // leftjoin with role
            ->leftJoin('roles', 'users_departments.position', '=', 'roles.id')

            // exclude users with superadmin role
            ->whereNotExists(function ($query) {
                // superadmin role name
                $query->select(DB::raw(1))
                    ->from('model_has_roles')
                    ->whereRaw('model_has_roles.model_id = users.id')
                    ->where('model_has_roles.role_id', '=', 1);
            });
    }


    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // order by year desc and  nim asc
            ->orderBy(5, 'desc')
            ->orderBy(2, 'desc')
            ->buttons([
                Button::make('')
                    ->text('<span class="fa fa-plus"></span>&nbsp; Tambah')
                    ->addClass('btnAdd'),
                Button::make()
                    ->text('<span class="fa fa-upload"></span>&nbsp; Import')
                    ->addClass('btnImport'),
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
                ->printable(false)
                ->exportable(false)
                ->title(
                    '<input type="checkbox" class="form-check-input" id="checkAll">'
                )
                ->render('function() {
                    return `<input type="checkbox" class="form-check-input checkItem" name="id[]" value="${this.id}" data-id="${this.id}">`;
            }'),
            Column::make('No')
                ->width(10)
                ->addClass('text-center')
                ->title('No')
                ->render('function() {
                    return this.No;
                }'),
            Column::make('nim')
                ->width(20)
                ->addClass('text-center')
                ->title('NIM')
                ->sortable(),
            Column::make('avatar')
                ->width(60)
                ->title('Foto')
                ->render('function() {
                    if (this.avatar) {
                        return `<img src="/storage/${this.avatar}" class="img-fluid rounded bg-primary" style="width: 40px;">`
                    } else {
                        return `<img src="/img/default_avatar.png" class="img-fluid rounded bg-primary" style="width: 40px;">`
                    }
                }')
                ->addClass('text-center'),
            Column::make('name')
                ->width(100)
                ->title('Nama')
                ->sortable(),
            Column::make('departments.name')
                ->width(100)
                ->addClass('text-center')
                ->title('Departemen')
                ->render('function() {
                    if (this.departments) {
                        return this.departments
                    } else {
                        return `Belum Ada`
                    }
                }'),
            Column::make('roles.name')
                ->width(70)
                ->addClass('text-center')
                ->title('Role')
                ->render('function() {
                    if (this.roles) {
                        return this.roles
                    } else {
                        return `Belum Ada`
                    }
                }'),
            Column::make('year')
                ->width(60)
                ->addClass('text-center')
                ->title('Angkatan'),
            Column::make('users_departments.is_active')
                ->width(60)
                ->addClass('text-center')
                ->title('Status')
                ->render('function() {
                    if (this.status == 1) {
                        return `<span class="badge bg-success">Aktif</span>`
                    } else {
                        return `<span class="badge bg-danger">Tidak Aktif</span>`
                    }
                }'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->addClass('text-center')
                ->title('Opsi')
                ->render('function() {
                    return `
                        <a class="btnEdit btn btn-ghost-primary  btn-sm fa fa-edit" data-action="' . route('users.edit', ':id') . '" data-id="${this.id}"></a>
                        <a class="btnDelete btn btn-ghost-danger btn-sm fa fa-trash" data-action="' . route('users.destroy', ':id') . '" data-id="${this.id}"></a>
                    `
                }')
                ->width(50),
        ];
    }

    protected function filename(): string
    {
        return 'Users_' . date('Y-m-d_H-i-s');
    }
}

<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('roles.index');
    }
}

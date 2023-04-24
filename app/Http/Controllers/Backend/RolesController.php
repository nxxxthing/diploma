<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public $module = 'roles';

    public function index(Request $request)
    {
        abort_unless(\Gate::allows($this->module . '_access'), 403);

        return view('admin.view.' . $this->module . '.index', ['module' => $this->module]);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $permissions = Permission::all()->pluck('title', 'id');

        return view(
            'admin.view.' . $this->module . '.create',
            [
                'permissions' => $permissions,
                'model'       => new Role(),
                'module'      => $this->module
            ]
        );
    }

    public function edit(Role $role)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        return view(
            'admin.view.' . $this->module . '.edit',
            [
                'model' => $role,
                'module' => $this->module
            ]
        );
    }
}

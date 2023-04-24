<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionsController extends Controller
{
    public $module = 'permissions';

    public function index()
    {
        abort_unless(\Gate::allows($this->module . '_access'), 403);

        return view('admin.view.' . $this->module . '.index', ['module' => $this->module]);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        return view(
            'admin.view.' . $this->module . '.create',
            [
                'module' => $this->module,
                'model' => new Permission()
            ]
        );
    }

    public function edit(Permission $permission)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        return view('admin.view.' . $this->module . '.edit', ['model' => $permission, 'module' => $this->module]);
    }
}

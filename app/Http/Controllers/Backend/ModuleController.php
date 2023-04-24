<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Module;

class ModuleController extends Controller
{
    public $module = 'modules';

    public function index()
    {
        abort_unless(\Gate::allows($this->module . '_access'), 403);

        return view('admin.view.' . $this->module . '.index', ['module' => $this->module]);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $data['module'] = $this->module;

        $data['model'] = new Module();

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit(Module $module)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        $data['module'] = $this->module;

        $data['model'] = $module;

        return view('admin.view.' . $this->module . '.edit', $data);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Models\Faculty;
use App\Http\Controllers\Controller;

class FacultyController extends Controller
{
    public string $module = 'faculties';

    public function index()
    {

        abort_unless(\Gate::allows($this->module . '_access'), 403);

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.index', $data);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $data['model'] = new Faculty();

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit(Faculty $faculty)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        $data['module'] = $this->module;

        $data['model'] = $faculty;

        return view('admin.view.' . $this->module . '.edit', compact('faculty'), $data);
    }
}

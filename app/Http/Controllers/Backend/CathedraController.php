<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Models\Cathedra;
use App\Http\Controllers\Controller;

class CathedraController extends Controller
{
    public string $module = 'cathedras';

    public function index()
    {

        abort_unless(\Gate::allows($this->module . '_access'), 403);

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.index', $data);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $data['model'] = new Cathedra();

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit(Cathedra $cathedra)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        $data['module'] = $this->module;

        $data['model'] = $cathedra->load(['faculty' => fn ($q) => $q->withTranslations()]);

        return view('admin.view.' . $this->module . '.edit', $data);
    }
}

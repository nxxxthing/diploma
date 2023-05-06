<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Models\Group;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    public string $module = 'groups';

    public function index()
    {

        abort_unless(\Gate::allows($this->module . '_access'), 403);

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.index', $data);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $data['model'] = new Group();

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit(Group $group)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        $data['module'] = $this->module;

        $data['model'] = $group->load([
            'cathedra' => fn ($query) => $query->withTranslations()->with([
                'faculty' => fn ($q) => $q->withTranslations()
            ])
        ]);

        return view('admin.view.' . $this->module . '.edit', $data);
    }
}

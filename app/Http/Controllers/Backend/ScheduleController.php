<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Models\Group;
use App\Models\Schedule;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public string $module = 'schedules';

    public function create(Group $group)
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $data['model'] = new Schedule();

        $data['group'] = $group;

        $data['module'] = $this->module;

        $data['back_url'] = route('admin.groups.edit', ['group' => $group]);

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit(Group $group, Schedule $schedule)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        $data['module'] = $this->module;

        $data['model'] = $schedule;

        $data['group'] = $group;

        $data['back_url'] = route('admin.groups.edit', ['group' => $group]);

        return view('admin.view.' . $this->module . '.edit', $data);
    }
}

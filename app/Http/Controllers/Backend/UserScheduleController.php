<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Enums\WeekTypes;
use App\Http\Controllers\Controller;

class UserScheduleController extends Controller
{
    public function student()
    {
        abort_unless(\Gate::allows('schedules_access'), 403);

        $user = auth('web')->user();

        $schedules = $user->group->schedules()->orderBy('day')->orderBy('time')->get();

        $data['schedules'] = [
            WeekTypes::FIRST->value => $schedules->where('week', WeekTypes::FIRST->value),
            WeekTypes::SECOND->value => $schedules->where('week', WeekTypes::SECOND->value),
        ];
        return view('admin.view.students.schedule', $data);
    }

    public function teacher()
    {
        abort_unless(\Gate::allows('schedules_access'), 403);

        $user = auth('web')->user();

        $schedules = $user->schedules()->orderBy('day')->orderBy('time')->get();

        $data['schedules'] = [
            WeekTypes::FIRST->value => $schedules->where('week', WeekTypes::FIRST->value),
            WeekTypes::SECOND->value => $schedules->where('week', WeekTypes::SECOND->value),
        ];
        return view('admin.view.students.schedule', $data);
    }
}

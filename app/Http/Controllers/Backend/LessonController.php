<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Models\Lesson;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
    public string $module = 'lessons';

    public function index()
    {

        abort_unless(\Gate::allows($this->module . '_access'), 403);

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.index', $data);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $data['model'] = new Lesson();

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit(Lesson $lesson)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        $data['module'] = $this->module;

        $data['model'] = $lesson;

        return view('admin.view.' . $this->module . '.edit', $data);
    }
}

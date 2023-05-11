<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Enums\WeekTypes;
use App\Http\Controllers\Controller;
use App\Models\Progress;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProgressController extends Controller
{
    public function student()
    {
        abort_unless(\Gate::allows('progress_access'), 403);

        return view('admin.view.students.progress.index');
    }

    public function teacher()
    {
        abort_unless(\Gate::allows('progress_access'), 403);

        return view('admin.view.teacher.progress.index');
    }

    public function create()
    {
        abort_unless(\Gate::allows('progress_create'), 403);

        $data['model'] = new Progress();

        return view('admin.view.students.progress.create', $data);
    }

    public function studentEdit(Progress $progress)
    {
        abort_unless(\Gate::allows('progress_edit') && auth('web')->id() == $progress->user_id, 403);

        $data['model'] = $progress;

        return view('admin.view.students.progress.edit', $data);
    }

    public function teacherEdit(Progress $progress)
    {
        abort_unless(
            \Gate::allows('progress_edit')
            && in_array($progress->user_id, auth('web')->user()->students()->pluck('id')->toArray()),
            403
        );

        $data['model'] = $progress;

        return view('admin.view.teacher.progress.edit', $data);
    }

    public function download(Progress $progress)
    {
        abort_unless(
            \Gate::allows('progress_edit')
            && in_array($progress->user_id, auth('web')->user()->students()->pluck('id')->toArray()),
            403
        );


        if (! Storage::exists($progress->file)) {
            throw new NotFoundHttpException();
        }

        return response()->download(Storage::path($progress->file));
    }
}

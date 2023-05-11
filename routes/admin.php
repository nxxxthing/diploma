<?php

declare(strict_types=1);

use App\Http\Controllers\Backend\{AdminSettingController,
    AuthController,
    CathedraController,
    FacultyController,
    GroupsController,
    HomeController,
    LessonController,
    PermissionsController,
    ProgressController,
    RolesController,
    ScheduleController,
    UserScheduleController,
    TranslationController,
    UploaderController,
    UserController,
    VariableController,
    ModuleController};
use Illuminate\Support\Facades\Route;

Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect()->back();
})->name('locale');

Route::group(
    [
        'as' => 'admin.'
    ],
    function () {
        Route::group(
            [
                'middleware' => 'auth',
            ],
            function () {
                Route::resource('faculties', FacultyController::class)->only('index', 'create', 'edit');
                Route::resource('cathedras', CathedraController::class)->only('index', 'create', 'edit');
                Route::resource('groups', GroupsController::class)->only('index', 'create', 'edit');
                Route::resource('lessons', LessonController::class)->only('index', 'create', 'edit');
                Route::resource('groups/{group}/schedules', ScheduleController::class)->only('create', 'edit');

                Route::get('student-schedule', [UserScheduleController::class, 'student'])->name('students.schedule');
                Route::get('student-progress', [ProgressController::class, 'student'])->name('students.progress');
                Route::get('student-progress/create', [ProgressController::class, 'create'])->name('progress.create');
                Route::get('student-progress/{progress}/edit', [ProgressController::class, 'studentEdit'])->name('students.progress.edit');

                Route::get('teacher-schedule', [UserScheduleController::class, 'teacher'])->name('students.schedule');
                Route::get('teacher-progress', [ProgressController::class, 'teacher'])->name('teacher.progress');
                Route::get('teacher-progress/{progress}/edit', [ProgressController::class, 'teacherEdit'])->name('teacher.progress.edit');
                Route::get('progress/{progress}/download', [ProgressController::class, 'download'])->name('progress.download')
                    ->whereIn(
                        'progress',
                        \App\Models\Progress::where('type', \App\Enums\ProgressTypes::FILE->value)
                            ->pluck('id')->toArray()
                    );

                Route::get('me', [UserController::class, 'me'])->name('users.self-edit');

                Route::get('users/new_password/{id}', [UserController::class, 'getNewPassword'])
                    ->name('users.new_password.get');
                Route::post('users/new_password/{id}', [UserController::class, 'postNewPassword'])
                    ->name('users.new_password.post');

                Route::resource('users/{type?}', UserController::class, ['except' => 'delete'])
                    ->parameter('{type?}', 'user')
                    ->names('users');
                Route::post('user/{id}/ajax_field', [UserController::class, 'ajaxFieldChange'])
                    ->middleware('ajax')->name('user.ajax_field');

                Route::resource('permissions', PermissionsController::class);
                Route::resource('roles', RolesController::class);

                // translations
                Route::get('translation/{group}', [TranslationController::class, 'index'])->name('translation.index');

                Route::post('translation/{group}', [TranslationController::class, 'update'])
                    ->name('translation.update');

                Route::post('translations/upload', [TranslationController::class, 'uploadFromFile'])
                    ->name('translations.upload');

                Route::get('translations/conflict', [TranslationController::class, 'conflicts'])
                    ->name('translations.conflicts');

                Route::put('translations/conflict/resolve', [TranslationController::class, 'resolveConflicts'])
                    ->name('translations.conflicts.resolve');

                Route::post('translations/conflict/force', [TranslationController::class, 'forceMergeConflicts'])
                    ->name('translations.conflicts.force');

                // variables
                Route::get('variables/list', [VariableController::class, 'list'])->name('variables.list.index');
                Route::post('variables/{id}/ajax_field', [VariableController::class, 'ajaxFieldChange'])
                    ->middleware('ajax')->name('variables.ajax_field');
                Route::resource('variables', VariableController::class)->except('show');

                Route::get('admin_settings', [AdminSettingController::class, 'index'])->name('admin_settings.index');
                Route::put('admin_settings', [AdminSettingController::class, 'update'])->name('admin_settings.update');
                Route::post('admin_settings/set_default', [AdminSettingController::class, 'setDefault'])
                    ->name('admin_settings.set_default');

                Route::post('upload/video', [UploaderController::class, 'uploadVideo'])->name('upload.video');

                Route::post('modules/{id}/ajax_field', [ModuleController::class, 'ajaxFieldChange'])
                    ->middleware('ajax')->name('modules.ajax_field');
                Route::resource('modules', ModuleController::class);
            }
        );

        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [HomeController::class, 'index'])->name('home');
    }
);

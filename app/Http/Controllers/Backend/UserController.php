<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public string $module = 'users';

    public function index()
    {
        abort_unless(\Gate::allows($this->module . '_access'), 403);

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.index', $data);
    }

    public function create()
    {
        abort_unless(\Gate::allows($this->module . '_create'), 403);

        $data['model'] = new User();

        $data['module'] = $this->module;

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit(User $user)
    {
        abort_unless(\Gate::allows($this->module . '_edit'), 403);

        $user->load('roles');

        $data['module'] = $this->module;

        $data['model'] = $user;

        return view('admin.view.' . $this->module . '.edit', compact('user'), $data);
    }
}

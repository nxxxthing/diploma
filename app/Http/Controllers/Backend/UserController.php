<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Api\v1\Enums\UserRoles;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public string $module = 'users';

    public function index($type = "")
    {

        abort_unless(\Gate::allows($type . '_access'), 403);

        $data['module'] = $this->module;
        $data['type'] = $type;

        return view('admin.view.' . $this->module . '.index', $data);
    }

    public function me()
    {
        $data['model'] = auth('web')->user();

        return view('admin.view.users.self-edit', $data);
    }

    public function create($type = "")
    {
        abort_unless(\Gate::allows($type . '_create'), 403);

        $data['model'] = new User();

        $data['module'] = $this->module;

        $data['type'] = $type;

        return view('admin.view.' . $this->module . '.create', $data);
    }

    public function edit($type, User $user)
    {
        abort_unless(\Gate::allows($type . '_edit'), 403);

        $user->load('role');

        $data['module'] = $this->module;

        $data['model'] = $user;

        $data['type'] = $type;

        return view('admin.view.' . $this->module . '.edit', compact('user'), $data);
    }
}

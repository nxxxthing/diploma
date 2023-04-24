<?php

namespace App\Api\v1\Http\Controllers;

use App\Api\v1\Http\Resources\VariableResource;
use App\Http\Controllers\Controller;
use App\Models\Variable;

class VariableController extends Controller
{
    public function index()
    {
        $variables = Variable::visible()
            ->orderBy('group')
            ->orderBy('in_group_position')
            ->withTranslations()
            ->get();

        return VariableResource::collection($variables);
    }
}

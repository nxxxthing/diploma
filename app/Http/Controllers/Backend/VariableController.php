<?php

namespace App\Http\Controllers\Backend;

use App\Classes\FileUploader;
use App\Http\Controllers\Controller;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Requests\Backend\Variable\VariablesRequest;
use App\Models\Variable;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VariableController extends Controller
{
    public $module = 'variables';

    private $data = [];

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $locales;

    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->locales = config('app.locales', ['en','ru','ua']);
        $this->fileUploader = $fileUploader;
        $this->data('locales', $this->locales);
        $this->data('module', $this->module);
    }

    public function index(Request $request)
    {

        if ($request->get('draw')) {
            $list = Variable::joinTranslations()
                ->select([
                    'variables.*',
                    'variable_translations.content as content'
                ]);

            return $this->_datatable($list);
        }

        return $this->render('admin.view.variables.index', $this->data);
    }

    public function list()
    {
        $this->data([
            'variable_groups' => Variable::joinTranslations()
                ->select([
                    'variables.*',
                    'variable_translations.content as content'
                ])
                ->orderBy('in_group_position')
                ->get()->groupBy('group')
        ]);

        return $this->render('admin.view.variables.list');
    }

    public function create()
    {
        $types = [];

        foreach (Variable::types as $type) {
            $types[$type] = __('admin_labels.variables.type.' . $type);
        }
        $this->data(['types' => $types]);

        $groups = Variable::select()->groupBy('group')->pluck('group', 'group')->toArray();

        $this->data('groups', $groups);

        $this->data('model', new Variable());

        return $this->render('admin.view.variables.create', $this->data);
    }

    public function store(VariablesRequest $request)
    {
        $inputs = $request->except('_token', '_method');

        $inputs['status'] = $inputs['status'] ?? false;

        Variable::create($inputs);

        return redirect(route('admin.variables.index'));
    }

    public function edit(Variable $variable)
    {
        $types = [];

        foreach (Variable::types as $type) {
            $types[$type] = __('admin_labels.type.' . $type);
        }

        $this->data(['types' => $types]);

        $this->data('model', $variable);

        $groups = Variable::select()->groupBy('group')->pluck('group', 'group')->toArray();

        $this->data('groups', $groups);

        return $this->render('admin.view.variables.edit', $this->data);
    }

    public function update(VariablesRequest $request, Variable $variable)
    {
        $inputs = $request->except('_token', '_method');

        $inputs['status'] = $inputs['status'] ?? false;

        if ($request->has('list')) {
            $inputs = array_merge($inputs, $inputs[$variable->id]);
            unset($inputs[$variable->id]);

            if ($variable->type === Variable::type_IMAGE) {
                if ($variable->translatable) {
                    foreach ($this->locales as $locale) {
                        if (
                            $request->hasFile($variable->id . '.' . $locale . '.content')
                            || $inputs[$locale]['isRemoveImage']
                        ) {
                            $inputs[$locale]['content'] = $this->saveImage(
                                $request->file($variable->id . '.' . $locale . '.content')
                            );
                        }
                    }
                } else {
                    if (
                        $request->hasFile($variable->id . '.value')
                        || $inputs['isRemoveImage']
                    ) {
                        $inputs['value'] = $this->saveImage(
                            $request->file($variable->id . '.value')
                        );
                    }
                }
            } elseif ($variable->type == Variable::type_FILE) {
                if ($variable->translatable) {
                    foreach (LocaleMiddleware::$languages as $locale) {
                        if ($inputs[$locale]['isRemoveImage']) {
                            if ($variable->translate($locale)->content) {
                                $this->fileUploader->delete($variable->translate($locale)->content);
                            }

                            $inputs[$locale]['content'] = $inputs[$locale]['content'] ?? null;
                        }

                        if (isset($inputs[$locale]['content'])) {
                            try {
                                if ($variable->translate($locale)->content ?? false) {
                                    $this->fileUploader->delete($variable->translate($locale)->content);
                                }
                            } catch (\Exception $e) {
                                //
                            }
                            $inputs[$locale]['content'] = $this->fileUploader->putAs($inputs[$locale]['content'], 'file', $this->module, $locale . '_' . $variable->key);
                        }
                    }
                } else {
                    if ($request->boolean('isRemoveImage')) {
                        if ($variable->value) {
                            $this->fileUploader->delete($variable->value);
                        }

                        $inputs['value'] = $inputs['value'] ?? null;
                    }

                    if (isset($inputs['value'])) {
                        $inputs['value'] = $this->fileUploader->putAs($inputs['value'], 'file', $this->module, $variable->key);
                        try {
                            if ($variable->value ?? false) {
                                $this->fileUploader->delete($variable->value);
                            }
                        } catch (\Exception $e) {
                            //
                        }
                    }
                }
            }
        }

        $variable->update($inputs);


        return redirect(route($request->has('list') ? 'admin.variables.list.index' : 'admin.variables.index'));
    }

    public function destroy(Variable $variable)
    {
        if ($variable->type == Variable::type_IMAGE) {
            if ($variable->translatable) {
                foreach ($this->locales as $locale) {
                    $this->removeOneImage($variable->translate($locale)->content);
                }
            } else {
                $this->removeOneImage($variable->value);
            }
        }

        $variable->delete();

        return back();
    }

    private function _datatable($list)
    {
        return DataTables::of($list)
            ->filterColumn(
                'actions',
                function ($query, $keyword) {
                    $query->whereRaw($this->module . '.name like ?', ['%' . $keyword . '%']);
                }
            )
            ->addColumn(
                'actions',
                function ($model) {
                    return view(
                        'admin.view.variables.partials.control_buttons',
                        ['model' => $model, 'front_link' => true, 'type' => $this->module]
                    )->render();
                }
            )
            ->editColumn(
                'type',
                function ($model) {
                    return __('admin_labels.variables.type.' . $model->type ?? '');
                }
            )
            ->editColumn(
                'status',
                function ($model) {
                    return view('datatables.toggler',
                        ['model' => $model, 'type' => $this->module, 'field' => 'status']
                    )->render();
                }
            )
            ->editColumn(
                'translatable',
                function ($model) {
                    /** @var \App\Models\Variable $model */
                    $icon = $model->translatable ? 'fas fa-check' : 'fas fa-times';
                    $color = $model->translatable ? 'text-success' : 'text-danger';

                    return sprintf(
                        '<div class="row"><span class="col-12 text-center %s"><i class="%s"></i></span></div>'
                        , $color, $icon
                    );
                }
            )
            ->rawColumns(['actions', 'in_group_position', 'status', 'type', 'translatable'])
            ->make();
    }

}

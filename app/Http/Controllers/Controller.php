<?php

namespace App\Http\Controllers;

use App\Models\Variable;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public $viewData = [];

    public function render($view = '', array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $this->data($k, $v);
            }
        }

        if (empty($view) || !view()->exists($view)) {
            $view = $this->_view;
        }

        $this->setVariables();

        if (view()->exists($view)) {
            return view($view, $this->viewData)->render();
        }

        throw new Exception('View not found', 500);
    }

    public function data()
    {
        $data = func_get_args();

        if (!empty($data)) {
            if (count($data) > 1) {
                $this->viewData[$data[0]] = $data[1];
            } elseif (is_array($data[0])) {
                $this->viewData = array_merge($this->viewData, $data[0]);
            } else {
                return false;
            }
        }

        return true;
    }

    protected function setVariables()
    {
        $variables = Variable::where('translatable', false)
            ->visible()
            ->get()
            ->pluck('value', 'key')
            ->toArray();

        $variables_translated = Variable::withTranslation()
            ->where('translatable', true)
            ->visible()
            ->get()
            ->pluck('content', 'key')
            ->toArray();

        $this->data('variables', array_merge($variables, $variables_translated));
    }
}

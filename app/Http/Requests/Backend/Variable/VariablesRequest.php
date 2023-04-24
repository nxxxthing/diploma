<?php

namespace App\Http\Requests\Backend\Variable;

use App\Models\Variable;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class VariablesRequest
 * @package App\Http\Requests
 *
 * @mixin Variable
 */
class VariablesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if($this->request->has('list')){
            $value = $this->getValueValidation();

            $rules_upd_list = [
                'status'                => 'nullable|boolean',
                'value'                 => $value,
            ];

            $translatedAttributes = [
                'content'              => $value,
            ];

            foreach ($translatedAttributes as $attribute => $rule) {
                foreach (config('app.locales') as $locale) {
                    $rules_upd_list["$locale.$attribute"] = $rule;
                }
            }

            foreach ($rules_upd_list as $attr => $rule){
                $rules['*.'.$attr] = $rule;
            }
        }else{
            $rules_create_save = [
                'key'                   => 'required|string|max:10000',
                'name'                  => 'required|string|max:10000',
                'type'                  => 'required|string|in:'.implode(',', Variable::types),
                'translatable'          => 'required|boolean',

                'description'           => 'nullable|string|max:10000',
                'group'                 => 'nullable|string|max:10000',
                'in_group_position'     => 'numeric|max:10000|min:1',
            ];

            $rules = array_merge($rules, $rules_create_save);
        }

        return $rules;
    }

    private function getValueValidation()
    {
        $type = $this->input('type', Variable::type_TEXT);

        return match ($type) {
            Variable::type_IMAGE => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10000',
            Variable::type_FILE => 'nullable|file|max:10000',
            default => 'nullable|string|max:10000',
        };
    }
}

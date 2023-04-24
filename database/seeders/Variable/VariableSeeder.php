<?php

namespace Database\Seeders\Variable;

use App\Models\Variable;
use Database\Seeders\BaseSeeder;

class VariableSeeder extends BaseSeeder
{
    public function run()
    {
        $data = [
//            [
//                'key' => 'phone',
//                'type' => Variable::type_TITLE,
//                'name' => 'Phone',
//                'description' => 'Phone in contacts',
//                'translatable' => false,
//                'group' => 'contacts',
//                'in_group_position' => 1,
//                'value' => '(8 658) 88400',
//                'status' => true,
//            ],
//            [
//                'key' => 'translatable',
//                'type' => Variable::type_TITLE,
//                'name' => 'translatable',
//                'description' => 'translatable',
//                'translatable' => true,
//                'group' => 'contacts',
//                'in_group_position' => 1,
//                'en' => [
//                    'content' => 'Department of Disability Affairs under the Ministry of Social Security and Labour',
//                ],
//                'status' => true,
//            ],
            [
                'key' => 'privacy_policy',
                'type' => Variable::type_FILE,
                'name' => 'Privacy policy',
                'description' => '',
                'translatable' => true,
                'group' => 'site',
                'in_group_position' => 1,
                'en' => [
                    'content' => '',
                ],
                'ua' => [
                    'content' => '',
                ],
                'status' => true,
            ],
        ];

        foreach ($data as $variable) {
            Variable::firstOrcreate(['key' => $variable['key']], $variable);
        }
    }
}

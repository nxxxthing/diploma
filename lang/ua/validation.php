<?php
return [

    'error' => 'Помилка валідації',


    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Последующие языковые строки содержат сообщения по-умолчанию, используемые
    | классом, проверяющим значения (валидатором). Некоторые из правил имеют
    | несколько версий, например, size. Вы можете поменять их на любые
    | другие, которые лучше подходят для вашего приложения.
    |
    */

    'accepted' => 'Ви повинні прийняти :attribute.',
    'active_url' => 'Поле :attribute містить недійсний URL.',
    'after' => 'У полі :attribute має бути дата більша :date.',
    'after_or_equal' => 'У полі :attribute має бути дата більша або дорівнює :date.',
    'alpha' => 'Поле :attribute може містити лише літери.',
    'alpha_dash' => 'Поле :attribute може містити лише літери, цифри, дефіс та нижнє підкреслення.',
    'alpha_num' => 'Поле :attribute може містити лише літери та цифри.',
    'array' => 'Поле :attribute має бути масивом.',
    'before' => 'У полі :attribute має бути дата раніше :date.',
    'before_or_equal' => 'У полі :attribute має бути дата раніше або дорівнювати :date.',
    'between' => [
        'numeric' => 'Поле :attribute має бути між :min та :max.',
        'file' => 'Розмір файлу в полі :attribute повинен бути між :min та :max Кілобайт(а).',
        'string' => 'Кількість символів у полі :attribute має бути між :min та :max.',
        'array' => 'Кількість елементів у полі :attribute має бути між :min та :max.',
    ],
    'boolean' => 'Поле :attribute повинно мати значення логічного типу.',
    'confirmed' => 'Поле :attribute не збігається з підтвердженням.',
    'date' => 'Поле :attribute не є датою.',
    'date_equals' => 'Поле :attribute має бути датою рівною :date.',
    'date_format' => 'Поле :attribute не відповідає формату :format.',
    'different' => 'Поля :attribute та :other повинні відрізнятися.',
    'digits' => 'Довжина цифрового поля :attribute повинна бути :digits.',
    'digits_between' => 'Довжина цифрового поля :attribute повинна бути між :min та :max.',
    'dimensions' => 'Поле :attribute має неприпустимі розміри зображення.',
    'distinct' => 'Поле :attribute містить значення, що повторюється.',
    'email' => 'Поле :attribute має бути дійсною електронною адресою.',
    'ends_with' => 'Поле :attribute має закінчуватися одним із наступних значень: :values',
    'exists' => 'Вибране значення для :attribute неправильне.',
    'file' => 'Поле :attribute має бути файлом.',
    'filled' => 'Поле :attribute обов\'язково для заповнення.',
    'gt' => [
        'numeric' => 'Поле :attribute має бути більше :value.',
        'file' => 'Розмір файлу в полі :attribute повинен бути більшим :value Кілобайт(а).',
        'string' => 'Кількість символів у полі :attribute має бути більшою :value.',
        'array' => 'Кількість елементів у полі :attribute має бути більшою :value.',
    ],
    'gte' => [
        'numeric' => 'Поле :attribute має бути :value або більше.',
        'file' => 'Розмір файлу в полі :attribute повинен бути :value Кілобайт(а) або більше.',
        'string' => 'Кількість символів у полі :attribute має бути :value або більше.',
        'array' => 'Кількість елементів у полі :attribute має бути :value або більше.',
    ],
    'image' => 'Поле :attribute має бути зображенням.',
    'in' => 'Вибране значення для :attribute помилкове.',
    'in_array' => 'Поле :attribute не існує в :other.',
    'integer' => 'Поле :attribute має бути цілим числом.',
    'ip' => 'Поле :attribute має бути дійсною IP - адресою.',
    'ipv4' => 'Поле :attribute має бути дійсною IPv4 - адресою.',
    'ipv6' => 'Поле :attribute має бути дійсною IPv6 - адресою.',
    'json' => 'Поле :attribute має бути рядком JSON.',
    'lt' => [
        'numeric' => 'Поле :attribute має бути менше :value.',
        'file' => 'Розмір файлу в полі :attribute повинен бути меншим :value Кілобайт(а).',
        'string' => 'Кількість символів у полі :attribute має бути меншою :value.',
        'array' => 'Кількість елементів у полі :attribute має бути меншою :value.',
    ],
    'lte' => [
        'numeric' => 'Поле :attribute має бути :value або менше.',
        'file' => 'Розмір файлу в полі :attribute повинен бути :value Кілобайт(а) або менше.',
        'string' => 'Кількість символів у полі :attribute має бути :value або менше.',
        'array' => 'Кількість елементів у полі :attribute має бути :value або менше.',
    ],
    'max' => [
        'numeric' => 'Поле :attribute не може бути більше :max.',
        'file' => 'Розмір файлу в полі :attribute не може бути більшим :max Кілобайт(а).',
        'string' => 'Кількість символів у полі :attribute не може перевищувати :max.',
        'array
    ' => 'Кількість елементів у полі :attribute не може перевищувати :max.',
    ],
    'mimes' => 'Поле :attribute має бути файлом одного з таких типів: :values.',
    'mimetypes' => 'Поле :attribute має бути файлом одного з таких типів: :values.',
    'min' => [
        'numeric' => 'Поле :attribute має бути не менше :min.',
        'file' => 'Розмір файлу в полі :attribute повинен бути не меншим :min Кілобайт(а).',
        'string' => 'Кількість символів у полі :attribute повинна бути не меншою :min.',
        'array
    ' => 'Кількість елементів у полі :attribute повинна бути не меншою :min.',
    ],
    'multiple_of' => 'Значення поля :attribute має бути кратним :value',
    'not_in' => 'Вибране значення для :attribute помилкове.',
    'not_regex' => 'Вибраний формат для :attribute помилковий.',
    'numeric' => 'Поле :attribute має бути числом.',
    'password' => 'Неправильний пароль.',
    'present' => 'Поле :attribute має бути присутнім.',
    'regex' => 'Поле :attribute має помилковий формат.',
    'required' => 'Поле :attribute обов\'язково для заповнення.',
    'required_if' => 'Поле :attribute обов\'язково для заповнення, коли :other одно :value.',
    'required_unless' => 'Поле :attribute обов\'язково для заповнення, коли :other не дорівнює :values.',
    'required_with' => 'Поле :attribute обов\'язково для заповнення, коли :values вказано .',
    'required_with_all' => 'Поле :attribute обов\'язково для заповнення, коли :values вказано.',
    'required_without' => 'Поле :attribute обов\'язково для заповнення, коли :values не вказано .',
    'required_without_all' => 'Поле :attribute обов\'язково для заповнення, коли жодне з :values не вказано.',
    'same' => 'Значення полів :attribute та :other повинні збігатися.',
    'size' => [
        'numeric' => 'Поле :attribute має бути рівним :size.',
        'file' => 'Розмір файлу в полі :attribute повинен дорівнювати :size Кілобайт(а).',
        'string' => 'Кількість символів у полі :attribute повинна бути рівною :size.',
        'array' => 'Кількість елементів у полі :attribute повинна бути рівною :size.',
    ],
    'starts_with' => 'Поле :attribute має починатися з одного з наступних значень: :values',
    'string' => 'Поле :attribute має бути рядком.',
    'timezone' => 'Поле :attribute має бути дійсним часовим поясом.',
    'unique' => 'Таке значення поля :attribute вже існує.',
    'uploaded' => 'Завантаження поля :attribute не вдалося.',
    'url' => 'Поле :attribute має помилковий формат URL.',
    'uuid' => 'Поле :attribute має бути коректним UUID.',

    /*
    |--------------------------------------------------------------------------
    | Собственные языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Здесь Вы можете указать собственные сообщения для атрибутов.
    | Это позволяет легко указать свое сообщение для заданного правила атрибута.
    |
    | http://laravel.com/docs/validation#custom-error-messages
    | Пример использования
    |
    |   'custom' => [
    |       'email' => [
    |           'required' => 'Нам необходимо знать Ваш электронный адрес!',
    |       ],
    |   ],
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Собственные названия атрибутов
    |--------------------------------------------------------------------------
    |
    | Последующие строки используются для подмены программных имен элементов
    | пользовательского интерфейса на удобочитаемые. Например, вместо имени
    | поля "email" в сообщениях будет выводиться "электронный адрес".
    |
    | Пример использования
    |
    |   'attributes' => [
    |       'email' => 'электронный адрес',
    |   ],
    |
    */

    'attributes' => [
        'name' => 'Ім\'я',
        'username' => 'Нікнейм',
        'email' => 'E - Mail',
        'first_name' => 'Ім\'я',
        'firstName' => 'Ім\'я',
        'last_name' => 'Прізвище',
        'lastName' => 'Прізвище',
        'password' => 'Пароль',
        'password_confirmation' => 'Підтвердження пароля',
        'current_password' => 'Поточний пароль',
        'city' => 'Місто',
        'country' => 'Країна',
        'address' => 'Адреса',
        'phone' => 'Телефон',
        'Mobile' => 'Моб. номер',
        'age' => 'Вік',
        'sex' => 'Стати',
        'gender' => 'Стати',
        'day' => 'День',
        'month' => 'Місяць',
        'year' => 'Рік',
        'hour' => 'Час',
        'minute' => 'Хвилина',
        'second' => 'Секунда',
        'title' => 'Назва',
        'content' => 'Контент',
        'description' => 'Опис',
        'excerpt' => 'Витримка',
        'date' => 'Дата',
        'time' => 'Година',
        'available' => 'Доступно',
        'size' => 'Розмір',
        'status' => 'Статус',
        'position' => 'Позиція',
        'quantity' => 'Кількість',
        'calories' => 'Ккал',
        'proteins' => 'Білки',
        'fats' => 'Жирі',
        'carbohydrates' => 'Вуглеводи',
    ],
];

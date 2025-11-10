<?php

return [

    // 他のバリデーションメッセージ...
    'required' => ':attribute は必須です。',
    'email' => ':attribute は有効なメールアドレス形式で入力してください。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],

    'password' => [
        'letters' => ':attribute には少なくとも1文字の英字を含めてください。',
        'mixed' => ':attribute には大文字と小文字の両方を含めてください。',
        'numbers' => ':attribute には少なくとも1つの数字を含めてください。',
        'symbols' => ':attribute には少なくとも1つの記号を含めてください。',
        'uncompromised' => '安全でないパスワードは使用できません。',
    ],

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],

];

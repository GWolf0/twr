<?php

return [

    'accepted' => ':attribute を承認してください。',
    'accepted_if' => ':other が :value の場合、:attribute を承認する必要があります。',
    'active_url' => ':attribute は有効なURLである必要があります。',
    'after' => ':attribute は :date より後の日付である必要があります。',
    'after_or_equal' => ':attribute は :date 以降の日付である必要があります。',
    'alpha' => ':attribute はアルファベットのみ使用できます。',
    'alpha_dash' => ':attribute はアルファベット・数字・ダッシュ・アンダースコアのみ使用できます。',
    'alpha_num' => ':attribute はアルファベットと数字のみ使用できます。',
    'array' => ':attribute は配列である必要があります。',
    'ascii' => ':attribute は1バイトの英数字と記号のみ使用できます。',
    'before' => ':attribute は :date より前の日付である必要があります。',
    'before_or_equal' => ':attribute は :date 以前の日付である必要があります。',

    'between' => [
        'array' => ':attribute は :min ～ :max 個である必要があります。',
        'file' => ':attribute は :min ～ :max KB の間である必要があります。',
        'numeric' => ':attribute は :min ～ :max の間である必要があります。',
        'string' => ':attribute は :min ～ :max 文字の間である必要があります。',
    ],

    'boolean' => ':attribute は true または false である必要があります。',
    'confirmed' => ':attribute の確認が一致しません。',
    'date' => ':attribute は有効な日付である必要があります。',
    'date_format' => ':attribute の形式は :format である必要があります。',
    'decimal' => ':attribute は小数点以下 :decimal 桁である必要があります。',
    'different' => ':attribute と :other は異なる必要があります。',
    'digits' => ':attribute は :digits 桁である必要があります。',
    'digits_between' => ':attribute は :min ～ :max 桁である必要があります。',
    'email' => ':attribute は有効なメールアドレスである必要があります。',
    'exists' => '選択された :attribute は無効です。',
    'file' => ':attribute はファイルである必要があります。',
    'filled' => ':attribute を入力してください。',
    'gt' => [
        'array' => ':attribute は :value 個より多く必要があります。',
        'file' => ':attribute は :value KB より大きい必要があります。',
        'numeric' => ':attribute は :value より大きい必要があります。',
        'string' => ':attribute は :value 文字より多い必要があります。',
    ],

    'gte' => [
        'array' => ':attribute は :value 個以上必要があります。',
        'file' => ':attribute は :value KB 以上である必要があります。',
        'numeric' => ':attribute は :value 以上である必要があります。',
        'string' => ':attribute は :value 文字以上である必要があります。',
    ],

    'image' => ':attribute は画像ファイルである必要があります。',
    'in' => '選択された :attribute は無効です。',
    'integer' => ':attribute は整数である必要があります。',
    'ip' => ':attribute は有効なIPアドレスである必要があります。',
    'json' => ':attribute は有効なJSON文字列である必要があります。',
    'max' => [
        'array' => ':attribute は :max 個以下である必要があります。',
        'file' => ':attribute は :max KB 以下である必要があります。',
        'numeric' => ':attribute は :max 以下である必要があります。',
        'string' => ':attribute は :max 文字以下である必要があります。',
    ],

    'min' => [
        'array' => ':attribute は :min 個以上である必要があります。',
        'file' => ':attribute は :min KB 以上である必要があります。',
        'numeric' => ':attribute は :min 以上である必要があります。',
        'string' => ':attribute は :min 文字以上である必要があります。',
    ],

    'numeric' => ':attribute は数値である必要があります。',
    'regex' => ':attribute の形式が正しくありません。',
    'required' => ':attribute は必須項目です。',
    'required_if' => ':other が :value の場合、:attribute は必須です。',
    'required_unless' => ':other が :values に含まれていない場合、:attribute は必須です。',
    'required_with' => ':values が存在する場合、:attribute は必須です。',
    'required_without' => ':values が存在しない場合、:attribute は必須です。',
    'same' => ':attribute と :other が一致している必要があります。',
    'size' => [
        'array' => ':attribute は :size 個である必要があります。',
        'file' => ':attribute は :size KB である必要があります。',
        'numeric' => ':attribute は :size である必要があります。',
        'string' => ':attribute は :size 文字である必要があります。',
    ],

    'string' => ':attribute は文字列である必要があります。',
    'timezone' => ':attribute は有効なタイムゾーンである必要があります。',
    'unique' => ':attribute は既に使用されています。',
    'uploaded' => ':attribute のアップロードに失敗しました。',
    'url' => ':attribute は有効なURLである必要があります。',
    'uuid' => ':attribute は有効なUUIDである必要があります。',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'カスタムメッセージ',
        ],
    ],

    'attributes' => [],

];

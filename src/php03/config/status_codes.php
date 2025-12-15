<?php
$status_codes = [
    [
    'type'=>'single',
    'code' => '102',
    'description' => '処理中である'
    ],
    [
    'type'=>'single',
    'code' => '200',
    'description' => 'リクエストが正常に成功できた'
    ],
    [
    'type'=>'single',
    'code' => '301',
    'description' => 'リクエストしたリソースが恒久的に移動されている'
    ],
    [
    'type'=>'single',
    'code' => '304',
    'description' => 'リクエストしたリソースは更新されていない'
    ],
    [
    'type'=>'single',
    'code' => '400',
    'description' => 'クライアントのリクエストに異常がある'
    ],
    [
    'type'=>'double',
    'code1' => '301',
    'code2' => '200',
    'description' => 'リクエストが成功したか、リソースが恒久的に移動されたことを示すステータスコードはどれ？'
    ],
    [
    'type'=>'double',
    'code1' => '404',
    'code2' => '400',
    'description' => 'クライアント側のリクエストに問題があることを示すステータスコードはどれ？'
    ],
    [
    'type'=>'double',
    'code1' => '503',
    'code2' => '500',
    'description' => 'サーバー側でエラーが発生したことを示すステータスコードはどれ？'
    ],
    [
    'type'=>'double',
    'code1' => '302',
    'code2' => '301',
    'description' => 'リダイレクトを示すステータスコードはどれ？'
    ],
    [
    'type' => 'double',
    'code1' => '200',
    'code2' => '204',
    'description' => 'リクエストが正常に処理されたことを示すステータスコードはどれ？'
    ],
    [
    'type'=>'text',
    'code' => '404',
    'description' => 'クライアント側のリクエストに問題があることを示すステータスコードはどれ？'
    ],
    [
    'type'=>'text',
    'code' => '503',
    'description' => 'サーバー側でエラーが発生したことを示すステータスコードはどれ？'
    ],
    [
    'type'=>'text',
    'code' => '302',
    'description' => 'リダイレクトを示すステータスコードはどれ？'
    ],
    [
    'type' => 'text',
    'code' => '200',
    'description' => 'リクエストが正常に処理されたことを示すステータスコードはどれ？'
    ],
];

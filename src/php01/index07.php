<?php
$people = [
    [
    "last_name" => "山田",
    "first_name" => "太郎",
    "age" => 29,
    "gender" => "男性"
    ],
    [
    "last_name" => "鈴木",
    "first_name" => "次郎",
    "age" => 25,
    "gender" => "男性"
    ],
    [
    "last_name" => "佐藤",
    "first_name" => "花子",
    "age" => 20,
    "gender" => "女性"
    ]
];
foreach ($people as $person) {
        echo $person;
        echo "<br/>";
    }
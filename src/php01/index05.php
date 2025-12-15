<?php
$a = 7;

if($a === 5) {
    echo "\$aは5です";
} elseif($a === 7) {
    echo "\$aは7です";
} else {
    echo "\$aは5以外です";
}

echo "<br/>";

$people = "Saburo";

switch ($people){
    case "Jiro":
    echo "次郎です";
    break;
    case "Itiro":
    echo "一郎です";
    break;
    default:
    echo "三郎です";
    break;
}

echo "<br/>";

$a = 7;
$b = ($a > 5) ? "正解" : "FALSE";
echo $b;

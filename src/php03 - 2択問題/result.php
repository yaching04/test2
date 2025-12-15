<?php
session_start();
require_once('config/status_codes.php');

//POSTで送信された値を取得
$answer_code1 = isset($_POST['answer_code1']) ? htmlspecialchars($_POST['answer_code1'], ENT_QUOTES) : null;
$answer_code2 = isset($_POST['answer_code2']) ? htmlspecialchars($_POST['answer_code2'], ENT_QUOTES) : null;
// ユーザーが選択したoptionを配列として扱う$select_optionsに格納
$select_options = isset($_POST['option']) ? array_map(function($v){ return htmlspecialchars($v, ENT_QUOTES); }, (array)$_POST['option']) : [];

// optionが選択されていない場合はindex.phpにリダイレクト
if (empty($select_options)) {
    header('Location: index.php');
    exit;
}

foreach($status_codes as $status_code){
    if($status_code['code1'] === $answer_code1 && $status_code['code2'] === $answer_code2){
        $code = $status_code['code1'] . ' , ' . $status_code['code2'];
        $description = $status_code['description'];
        break;
    }
}

// 正解判定
$result = (count($select_options) === 2) && in_array($answer_code1,$select_options, true) && in_array($answer_code2, $select_options, true);//選択肢の数が2つで、かつ正解コード両方が選ばれているか

if (!isset($_SESSION['quiz_count'])) {
    $_SESSION['quiz_count'] = 1;
    $_SESSION['correct_count'] = 0;
}else{
    $_SESSION['quiz_count'] ++;
    if ($result) {
        $_SESSION['correct_count'] ++;
    }
}

if($_SESSION['quiz_count'] < 6){
    $action  = "index.php";
    $button  = "次の問題へ";
} else {
    $action  = "all_result.php";
    $button  = "結果を見る";
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/result.css">
    <link rel="stylesheet" href="css/sanitize.css">

</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="/php03" class="header__logo">
                Status Code Quiz
            </a>
        </div>
    </header>
    <main>
        <div class="result__content">
            <div class="result">
                <?php if($result): ?>
                <h2 class="result__text--correct">正解</h2>
                <?php else: ?>
                <h2 class="result__text--incorrect">不正解</h2>
                <?php endif; ?>
            </div>
            <div class="answer-table">
                <table class="answer-table__inner">
                    <tr class="answer-table__row">
                        <th class="answer-table__header">ステータスコード</th>
                        <td class="answer-table__text"><?php echo $code ?></td>
                    </tr>
                    <tr class="answer-table__row">
                        <th class="answer-table__header">説明</th>
                        <td class="answer-table__text"><?php echo $description ?></td>
                    </tr>
                </table>
            </div>
            <div class="result">
                <form class="result-form" action="<?php echo $action; ?>" method="get">
                    <div class="result-form__button">
                        <button class="result-form__button-submit" type="submit"><?php echo $button; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
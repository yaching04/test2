<?php
session_start();

if (!isset($_SESSION['correct_count'])) {
    header('Location: index.php');
    exit;
}

$correct = $_SESSION['correct_count'];
$bonus = isset($_SESSION['bonus_points']) ? (int)$_SESSION['bonus_points'] : 0;
$score = $correct * 20 + $bonus;

if ($score <= 20) {
    $message = "もう少し頑張ろう！";
} elseif ($score <= 40) {
    $message = "まだまだ頑張って！";
} elseif ($score <= 60) {
    $message = "いいね!その調子!";
} elseif ($score === 80) {
    $message = "あと1問!頑張ろう！";
} else {
    $message = "完璧！";
}

// セッションリセット（次回クイズでカウント0から）
unset($_SESSION['quiz_count']);
unset($_SESSION['correct_count']);
unset($_SESSION['bonus_points']);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>最終結果</title>
    <link rel="stylesheet" href="css/sanitize.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/all_result.css">
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a href="index.php" class="header__logo">Status Code Quiz</a>
        </div>
    </header>
    <div class="result__content">
        <div class="result">
            <h2 class="result__text">お疲れ様でした!</h2>
        </div>
        <div class="answer-table">
            <table class="answer-table__inner">
                <tr class="answer-table__row">
                    <th class="answer-table__header">TotalScore</th>
                    <td class="answer-table__text1"><?php echo $score; ?>点</td>
                </tr>
                <tr class="answer-table__row">
                    <th class="answer-table__header">ボーナス</th>
                    <td class="answer-table__text"><?php echo $bonus; ?>点（5秒以内の正解）</td>
                </tr>
                <tr class="answer-table__row">
                    <th class="answer-table__header">一言</th>
                    <td class="answer-table__text"><?php echo $message; ?></td>
                </tr>
            </table>
        </div>
        <div class="result">
            <form class="result-form" action="index.php" method="get">
                <div class="result-form__button">
                    <button class="result-form__button-submit" type="submit">
                        もう一度挑戦する
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
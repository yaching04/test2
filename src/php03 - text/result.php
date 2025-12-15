<?php
session_start();
require_once('config/status_codes.php');

// ① セッションに保存された正解（文字列）を取り出します。 ② 正解が無ければ結果処理を中止するためにチェックします。 ③ (string) は型キャストで文字列に変換します。
$correct = isset($_SESSION['current_answer']) ? (string)$_SESSION['current_answer'] : null;

// ① 正解が無ければトップに戻します
if (!$correct) {
    header('Location: index.php');
    exit;
}

// ① フォームで送られた生の入力値を取り出します。 ② 未入力時は空文字にしておきます。
$user_raw = isset($_POST['user_answer']) ? (string)$_POST['user_answer'] : '';
// ① 前後の空白を取り除いて正規化します。
$user = trim($user_raw);

// ① ユーザー入力と正解が完全に一致するか比べます。 ② 一致すれば true（正解）、違えば false（不正解）になります。
$result = ($user === $correct);

// ① 正解の説明を入れるための空の変数を用意します。 ② 見つからなければ空のまま表示できます。
$description = '';
// ① status_codes 配列を1つずつ調べます。 ② 正解のコードと一致する説明を探すために使います。
foreach ($status_codes as $status) {
    // ① 現在の要素に 'code' があり、それが正解と一致するかを調べます。 ② 一致したらその説明を使います。
    if (isset($status['code']) && (string)$status['code'] === $correct) {
        // ① 見つけた説明を $description に代入します。 ② 結果画面に表示するためです。
        $description = $status['description'];
        break;
    }
}
// ① 表示用に正解を HTML エスケープして $code に入れます。 ② HTML の特殊文字を無害化して安全に表示するためです。 ③ htmlspecialchars() は特殊文字を変換する関数です。
$code = htmlspecialchars($correct, ENT_QUOTES);

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

unset($_SESSION['current_answer']);
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
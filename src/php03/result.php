<?php
session_start();
require_once('config/status_codes.php');

// --- 初期化 ---
$result = false;
$answer_code = '';
$answer_code1 = '';
$answer_code2 = '';
$display_code = '';
$description = '';
$type = null;
$timed_out = false;
$elapsed = null;

// セッション保険
if (!isset($_SESSION['quiz_count'])) $_SESSION['quiz_count'] = 1;
if (!isset($_SESSION['correct_count'])) $_SESSION['correct_count'] = 0;
if (!isset($_SESSION['bonus_points'])) $_SESSION['bonus_points'] = 0;

// リクエストが POST でない場合は index に戻す（直接アクセス防止）
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// type を受け取る（なければ index へ）
$type = isset($_POST['type']) ? (string)$_POST['type'] : null;
if (!$type) {
    header('Location: index.php');
    exit;
}

// サーバ側タイムチェック（index.php で設定した start_time を使用）
$start_time = isset($_SESSION['start_time']) ? (int)$_SESSION['start_time'] : null;
if ($start_time !== null) $elapsed = time() - $start_time;
unset($_SESSION['start_time']);
if ($elapsed !== null && $elapsed > 15) $timed_out = true;

// 判定ロジック
// 注意：ここでは「未送信＝空データ」として処理し、早期リダイレクトは行わない
if ($type === 'single') {
    $selected = isset($_POST['option']) ? trim((string)$_POST['option']) : '';
    $answer_code = isset($_POST['answer_code']) ? trim((string)$_POST['answer_code']) : '';
    $result = ($selected !== '' && $selected === $answer_code);
}
elseif ($type === 'double') {
    $selected = isset($_POST['option']) ? array_map('trim', (array)$_POST['option']) : [];
    $answer_code1 = isset($_POST['answer_code1']) ? trim((string)$_POST['answer_code1']) : '';
    $answer_code2 = isset($_POST['answer_code2']) ? trim((string)$_POST['answer_code2']) : '';
    // 正解集合と比較（未選択は空配列）
    $selected_unique = array_values(array_unique($selected));
    $correct_set = array_values(array_filter([$answer_code1, $answer_code2], function($v){ return $v !== ''; }));
    sort($selected_unique); sort($correct_set);
    $result = (!empty($correct_set) && $selected_unique === $correct_set);
}
elseif ($type === 'text') {
    $user = isset($_POST['user_answer']) ? trim((string)$_POST['user_answer']) : '';
    $answer_code = isset($_POST['answer_code']) ? trim((string)$_POST['answer_code']) : '';
    $result = ($user !== '' && $user === $answer_code);
}
else {
    header('Location: index.php');
    exit;
}

// サーバ側タイムアウトは強制不正解（必ず result.php で判定）
if ($timed_out) $result = false;

// --- セッション更新 ---
$_SESSION['quiz_count'] = isset($_SESSION['quiz_count']) ? $_SESSION['quiz_count'] + 1 : 2;
if ($result) {
    $_SESSION['correct_count'] = isset($_SESSION['correct_count']) ? $_SESSION['correct_count'] + 1 : 1;
    if ($elapsed !== null && $elapsed <= 5) {
        $_SESSION['bonus_points'] = isset($_SESSION['bonus_points']) ? $_SESSION['bonus_points'] + 1 : 1;
    }
}

// --- 表示用正解と説明を探す ---
if ($type === 'double') {
    $display_code = trim($answer_code1 . ' , ' . $answer_code2);
    foreach ($status_codes as $s) {
        if (isset($s['code1'],$s['code2']) && (string)$s['code1'] === $answer_code1 && (string)$s['code2'] === $answer_code2) {
            $description = $s['description'] ?? '';
            break;
        }
    }
} else {
    $display_code = $answer_code;
    foreach ($status_codes as $s) {
        if (isset($s['code']) && (string)$s['code'] === $display_code) {
            $description = $s['description'] ?? '';
            break;
        }
    }
}

// --- 次ページ決定 ---
if ($_SESSION['quiz_count'] < 6) { $action = 'index.php'; $button = '次の問題へ'; }
else { $action = 'all_result.php'; $button = '結果を見る'; }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>結果</title>
<link rel="stylesheet" href="css/sanitize.css">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/result.css">
</head>
<body>
<header class="header"><div class="header__inner"><a class="header__logo" href="/php03">Status Code Quiz</a></div></header>
<main>
    <div class="result__content">
        <div class="result">
            <?php if ($result): ?>
                <h2 class="result__text--correct">正解</h2>
            <?php else: ?>
                <h2 class="result__text--incorrect">不正解</h2>
            <?php endif; ?>

            <?php if ($timed_out): ?>
                <p class="result__text--timeout">時間切れです（15秒超）。</p>
            <?php elseif ($result && $elapsed !== null && $elapsed <= 5): ?>
                <p class="result__text--fast">正解！ボーナス +1点を付与しました。</p>
            <?php endif; ?>
        </div>

        <div class="answer-table">
            <table class="answer-table__inner">
                <tr class="answer-table__row"><th class="answer-table__header">ステータスコード</th><td class="answer-table__text"><?php echo htmlspecialchars($display_code, ENT_QUOTES); ?></td></tr>
                <tr class="answer-table__row"><th class="answer-table__header">説明</th><td class="answer-table__text"><?php echo htmlspecialchars($description, ENT_QUOTES); ?></td></tr>
                <tr class="answer-table__row"><th class="answer-table__header">経過時間</th><td class="answer-table__text"><?php echo ($elapsed!==null)?((int)$elapsed).' 秒':'不明'; ?></td></tr>
            </table>
        </div>

        <div class="result">
            <form class="result-form" action="<?php echo htmlspecialchars($action, ENT_QUOTES); ?>" method="get">
                <div class="result-form__button">
                    <button class="result-form__button-submit" type="submit"><?php echo htmlspecialchars($button, ENT_QUOTES); ?></button>
                </div>
            </form>
        </div>
    </div>
</main>
</body>
</html>
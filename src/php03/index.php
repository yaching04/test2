<?php
session_start();// セッションを開始。ページを移動しても「今何問目か」「正解数」などを覚えておくため
require_once('config/status_codes.php');//クイズの問題データを読み込む。

// セッション変数初期化
if (!isset($_SESSION['quiz_count'])) {//セッションに「quiz_count」がなければ初期化
    $_SESSION['quiz_count'] = 1;// 今何問目かを覚える（最初は1）
    $_SESSION['correct_count'] = 0;// 正解した数を覚える（最初は0）
    $_SESSION['bonus_points'] = 0;// 早押しボーナスを覚える（最初は0）
}

// $status_codes 配列からランダムに1問選び、$sample に格納
$sample = $status_codes[array_rand($status_codes)];
$type = isset($sample['type']) ? $sample['type'] : 'single';//今回はいらない

//　typeごとの問題に分ける。
$singles = array_values(array_filter($status_codes, function($s){ return isset($s['type'],$s['code']) && $s['type']==='single'; }));//array_filter は「条件に合うものだけ残す」関数。
//function($s){ 条件：'type' と 'code' があって、typeが'single'のものだけ残す。 }
//array_values で番号を0から振り直す。

$doubles = array_values(array_filter($status_codes, function($s){ return isset($s['type']) && $s['type']==='double'; }));

$texts = array_values(array_filter($status_codes, function($s){ return isset($s['type'],$s['code']) && $s['type']==='text'; }));

$message = '';// 問題文
$render_options = []; // 表示する選択肢
$hidden_fields = [];  // 隠して送る正解コード

if ($type === 'single') {// 問題の種類が 'single'（1択問題）の場合
    $message = 'Q.以下の内容に当てはまるステータスコードを選んでください';
    $codes = array_map(function($s){ return $s['code']; }, $singles);// 'single' 問題のコード一覧を取得
    if (count($codes) < 5) {// 選択肢が5つ未満の場合、他のコードも追加して補う
        $all_codes = array_values(array_filter(array_map(function($s){ return isset($s['code']) ? $s['code'] : null; }, $status_codes)));
        $codes = array_values(array_unique(array_merge($codes, $all_codes)));// 重複を排除して番号を振り直す
    }
    shuffle($codes);// 選択肢をランダムに並び替え
    $codes = array_slice($codes, 0, min(5, count($codes)));// 最初の5つを取得
    //実際に出題する問題をランダムに選ぶ
    $question = !empty($singles) ? $singles[array_rand($singles)] : $status_codes[array_rand($status_codes)];
    $answer_code = $question['code'] ?? '';// 正解コードを取得
    $hidden_fields[] = ['name'=>'type','value'=>'single'];// hidden_fields に type を追加
    $hidden_fields[] = ['name'=>'answer_code','value'=>$answer_code];
    foreach ($codes as $c) $render_options[] = ['value'=>$c,'label'=>$c];
}
elseif ($type === 'double') {// 2択問題
    $message = 'Q.以下の内容に当てはまるステータスコードを全て選んでください';
    $all_codes = [];
    foreach ($status_codes as $s) {// 全てdoubleのコードを収集
        if (isset($s['code1'])) $all_codes[] = $s['code1'];
        if (isset($s['code2'])) $all_codes[] = $s['code2'];
    }
    $all_codes = array_values(array_unique($all_codes));
    //実際に出題する問題をランダムに選ぶ
    $question = !empty($doubles) ? $doubles[array_rand($doubles)] : $status_codes[array_rand($status_codes)];
    //正解コードを取得
    $correct1 = $question['code1'] ?? '';
    $correct2 = $question['code2'] ?? '';
    // hidden_fields に type と正解コードを追加
    $hidden_fields[] = ['name'=>'type','value'=>'double'];
    $hidden_fields[] = ['name'=>'answer_code1','value'=>$correct1];
    $hidden_fields[] = ['name'=>'answer_code2','value'=>$correct2];
    // 選択肢を作成（正解2つ＋誤答3つ）
    $wrong_candidates = array_values(array_diff($all_codes, [$correct1, $correct2]));
    shuffle($wrong_candidates);
    $wrong_choices = array_slice($wrong_candidates, 0, 3);
    $options = array_merge([$correct1, $correct2], $wrong_choices);
    $options = array_values(array_filter($options, function($v){ return $v !== ''; }));
    shuffle($options);
    foreach ($options as $o) $render_options[] = ['value'=>$o,'label'=>$o];
}
else { // text問題
    $message = 'Q.以下の内容に当てはまるステータスコードを入力してください';
    $question = !empty($texts) ? $texts[array_rand($texts)] : $status_codes[array_rand($status_codes)];
    $answer_code = $question['code'] ?? '';
    $hidden_fields[] = ['name'=>'type','value'=>'text'];
    $hidden_fields[] = ['name'=>'answer_code','value'=>$answer_code];
}

// サーバ側タイマー開始
$_SESSION['start_time'] = time();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Status Code Quiz</title>
<link rel="stylesheet" href="css/sanitize.css">
<link rel="stylesheet" href="css/common.css">
<link rel="stylesheet" href="css/index.css">
</head>
<body>
<header class="header">
    <div class="header__inner">
        <a class="header__logo" href="/">Status Code Quiz</a>
    </div>
</header>

<main>
    <div class="quiz__content">
        <div class="question__num">第 <?php echo (int)$_SESSION['quiz_count']; ?> 問目 / 全5問</div>
        <div class="question">
            <p class="question__text"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
            <p class="question__text"><?php echo htmlspecialchars($question['description'] ?? '', ENT_QUOTES); ?></p>
        </div>

        <div id="countdown">残り <span id="timer">15</span> 秒</div>

        <form id="quizForm" class="quiz-form" action="result.php" method="post">
            <?php foreach ($hidden_fields as $h): ?>
                <input type="hidden" name="<?php echo htmlspecialchars($h['name'], ENT_QUOTES); ?>" value="<?php echo htmlspecialchars($h['value'], ENT_QUOTES); ?>">
            <?php endforeach; ?>

            <?php if (!empty($render_options)): ?>
                <div class="quiz-form__item">
                    <?php
                    $input_type = ($type === 'double') ? 'checkbox' : 'radio';
                    $input_name = ($type === 'double') ? 'option[]' : 'option';
                    foreach ($render_options as $opt):
                        $id = 'opt_' . htmlspecialchars($opt['value'], ENT_QUOTES);
                    ?>
                    <div class="quiz-form__group">
                        <input class="quiz-form__radio" id="<?php echo $id; ?>" type="<?php echo $input_type; ?>" name="<?php echo $input_name; ?>" value="<?php echo htmlspecialchars($opt['value'], ENT_QUOTES); ?>">
                        <label class="quiz-form__label" for="<?php echo $id; ?>"><?php echo htmlspecialchars($opt['label'], ENT_QUOTES); ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="quiz-form__item">
                    <div class="quiz-form__group-text">
                        <input class="text_form" id="user_answer" type="text" name="user_answer" placeholder="例: 200" required>
                        <label for="user_answer"></label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="quiz-form__button">
                <button id="submitBtn" class="quiz-form__button-submit" type="submit">回答</button>
            </div>
        </form>
    </div>
</main>

<script>
(function(){
    var timeLeft = 15;
    var timerEl = document.getElementById('timer');
    var form = document.getElementById('quizForm');
    var submitBtn = document.getElementById('submitBtn');
    var interval = setInterval(function(){
        timeLeft--;
        if (timeLeft < 0) timeLeft = 0;
        if (timerEl) timerEl.textContent = timeLeft;
        if (timeLeft <= 0) {
            clearInterval(interval);
            submitBtn.disabled = true;
            // 自動送信（result.php に POST されサーバ側で時間切れ判定）
            var f = document.createElement('input');
            f.type = 'hidden';
            f.name = 'timed_out_client';
            f.value = '1';
            form.appendChild(f);
            form.submit();
        }
    }, 1000);

    form.addEventListener('submit', function(){ clearInterval(interval); submitBtn.disabled = true; });
})();
</script>
</body>
</html>
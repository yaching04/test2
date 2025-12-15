<?php
session_start();   // 「何問目か」「何問正解したか」覚える箱
require_once('config/status_codes.php');

// 初回アクセス時は1問目にする
if (!isset($_SESSION['quiz_count'])) {   // sessionの中に'quiz_count'（何問目か）入ってないなら
    $_SESSION['quiz_count'] = 1;   // 1問目から
    $_SESSION['correct_count'] = 0;   // 正解数はまだ0
}

$question = $status_codes[array_rand($status_codes)];

$_SESSION['current_answer'] = $question['code'];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Code Quiz</title>
    <link rel="stylesheet" href="css/sanitize.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">
                Status Code Quiz
            </a>
        </div>
    </header>

    <main>
        <div class="quiz__content">
            <div class="question__num">
                第 <?php echo $_SESSION['quiz_count']; ?> 問目 / 全5問
            </div>
            <div class="question">
                <p class="question__text">Q. 以下の内容に当てはまるステータスコードを入力してください</p>
                <p class="question__text">
                <?php echo $question['description'] ?>
                </p>
            </div>
            <form class="quiz-form" action="result.php" method="post">
                    <div class="quiz-form__item">
                        <div class="quiz-form__group-text">
                            <input class="text_form" id="user_answer" type="text" name="user_answer" placeholder="例: 200" required>
                            <label for="user_answer"></label>
                        </div>
                    </div>
                    <div class="quiz-form__button">
                        <button class="quiz-form__button-submit" type="submit">回答</button>
                    </div>
            </form>
        </div>
    </main>
</body>

</html>

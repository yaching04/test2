<?php
session_start();//何問目かや正解数を覚える箱
require_once('config/status_codes.php');//$status_codes.phpの読み込み

// 初回アクセス時の処理
if (!isset($_SESSION['quiz_count'])) {//sessionの中に'quiz_count'（何問目か）入ってないなら
    $_SESSION['quiz_count'] = 1;//1問目から
    $_SESSION['correct_count'] = 0;//正解数はまだ0
}

// 選択肢の作成
$random_indexes = array_rand($status_codes, 5);//$status_codes配列からランダムに5つ取得

//選んだ5つの選択肢($random_indexes)を順番に処理し、$options配列に追加
foreach ($random_indexes as $index) {
    $options[] = $status_codes[$index];
}

//5つの選択肢から1つをランダムに選ぶ(正解の問題)
$question = $options[mt_rand(0, 4)];

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
                <p class="question__text">Q. 以下の内容に当てはまるステータスコードを選んでください</p>
                <p class="question__text">
                <?php echo $question['description'] ?>
                </p>
            </div>
            <form class="quiz-form" action="result.php" method="post">
                <input type="hidden" name="answer_code" value="<?php echo $question['code'] ?>">
                    <div class="quiz-form__item">
                        <?php foreach ($options as $option) : ?>
                        <div class="quiz-form__group">
                            <input class="quiz-form__radio" id="<?php echo $option['code'] ?>" type="radio" name="option" value="<?php echo $option['code'] ?>">
                            <label class="quiz-form__label" for="<?php echo $option['code'] ?>">
                            <?php echo $option['code'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <div class="quiz-form__button">
                        <button class="quiz-form__button-submit" type="submit">回答</button>
                    </div>
            </form>
        </div>
    </main>
</body>

</html>

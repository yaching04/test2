<?php
session_start();
require_once('config/status_codes.php');

if (!isset($_SESSION['quiz_count'])) {
    $_SESSION['quiz_count'] = 1;
    $_SESSION['correct_count'] = 0;
}

//$all_codesに全てのcode1とcode2を入れる
$all_codes = [];
foreach($status_codes as $status_code){
    $all_codes[] = $status_code['code1'];
    $all_codes[] = $status_code['code2'];
}
$all_codes = array_unique($all_codes);//重複は排除

//$status_codes 配列から1つランダムに取得
$question = $status_codes[array_rand($status_codes)];

//ランダムに1つ選んだ問題の正解コードを取得
$correct1 = $question['code1'];
$correct2 = $question['code2'];

$options = [$correct1, $correct2];//正解の2つを$options配列に入れる

$wrong_candidates = array_diff($all_codes, [$correct1, $correct2]);//正解以外のコードを取得
$wrong_choices = array_rand(array_flip($wrong_candidates), 3);  //正解以外のコードからランダムに3つ取得
$options = array_merge($options, $wrong_choices);//正解2つと不正解3つを合わせて$optionsに入れる
shuffle($options);//選択肢をシャッフル
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
                <p class="question__text">Q. 以下の内容に当てはまるステータスコードを全て選んでください</p>
                <p class="question__text">
                <?php echo $question['description'] ?>
                </p>
            </div>
            <form class="quiz-form" action="result.php" method="post">
                <input type="hidden" name="answer_code1" value="<?php echo $correct1; ?>">
                <input type="hidden" name="answer_code2" value="<?php echo $correct2; ?>">
                    <div class="quiz-form__item">
                        <?php foreach ($options as $option) : ?>
                        <div class="quiz-form__group">
                            <input class="quiz-form__radio" id="<?php echo $option; ?>" type="checkbox" name="option[]" value="<?php echo $option; ?>">
                            <label class="quiz-form__label" for="<?php echo $option; ?>">
                            <?php echo $option; ?>
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

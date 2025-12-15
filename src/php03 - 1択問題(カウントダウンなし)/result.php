<?php
session_start();//前ページで保存した何問目かや正解数を覚える箱
require_once('config/status_codes.php');

//POSTで受け取ったデータを変数に代入
$answer_code = isset($_POST['answer_code']) ? htmlspecialchars($_POST['answer_code'],ENT_QUOTES) : null;//hiddenで送られた正解のコード
$option = isset($_POST['option']) ? htmlspecialchars($_POST['option'], ENT_QUOTES) : null;//ユーザーが選んだ選択肢

//もし選択肢が空なら、index.phpにリダイレクト(未選択で結果処理しないため)
if (empty($option)) {
    header('Location: index.php');
    exit;
}

//正解のコードと合う問題の説明を$status_codes配列から探す
foreach ($status_codes as $status_code) {//$status_codes配列を順番に処理
    if ($status_code['code'] === $answer_code) {//正解のコードと一致したら
        $code = $status_code['code'];//その正解コードを$codeに代入
        $description = $status_code['description'];//その説明を$descriptionに代入
        break;//正解の問題が見つかったらループ終了
    }
}

$result = $option === $code;//ユーザーの選んだ選択肢と正解コードが一致するか確認し、$resultにtrue/falseを代入

//sessionに何問目か、正解数を保存・更新
if (!isset($_SESSION['quiz_count'])) {//sessionの中に'quiz_count'（何問目か）入ってないなら
    $_SESSION['quiz_count'] = 1;//1問目から
    $_SESSION['correct_count'] = 0;//正解数はまだ0
}else{//2問目以降の処理
    $_SESSION['quiz_count'] ++;//何問目かを1増やす
    if ($result) {//もし正解なら
        $_SESSION['correct_count'] ++;//正解数を1増やす
    }
}

//何問目かによって、次のページとボタンの表示を変える
if($_SESSION['quiz_count'] < 6){//5問未満なら
    $action  = "index.php";//index.phpへ
    $button  = "次の問題へ";//ボタンの表示は「次の問題へ」
} else {//5問目なら
    $action  = "all_result.php";//all_result.phpへ
    $button  = "結果を見る";//ボタンの表示は「結果を見る」
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
                <?php if($result): ?><!-- (条件分岐)もし正解なら -->
                <h2 class="result__text--correct">正解</h2>
                <?php else: ?><!--もし不正解なら -->
                <h2 class="result__text--incorrect">不正解</h2>
                <?php endif; ?><!-- 条件分岐終了 -->
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
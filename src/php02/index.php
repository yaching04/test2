<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hollo World</title>
    <link rel="stylesheet" href="css/common1.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/sanitize.css">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a href="/php02/index.php" class="header__logo">
                Hollo World
            </a>
        </div>
    </header>
    <main>
        <div class="search-form__content">
            <div class="search-form__heading">
                <h2 class="search-form__content-title">日本と世界の時間を比較</h2>
            </div>
            <form action="result.php" class="search-form" method="get">
                <div class="search-form__item">
                    <select class="search-form__item-select" name="city" id="">
                        <option value="シドニー">シドニー</option>
                        <option value="上海">上海</option>
                        <option value="モスクワ">モスクワ</option>
                        <option value="ロンドン">ロンドン</option>
                        <option value="ヨハネスブルグ">ヨハネスブルグ</option>
                        <option value="ニューヨーク">ニューヨーク</option>
                        <option value="パリ">パリ</option>
                        <option value="バンコク">バンコク</option>
                        <option value="ドバイ">ドバイ</option>
                        <option value="カイロ">カイロ</option>
                        <option value="デンバー">デンバー</option>
                    </select>
                </div>
                <div class="search-form__button">
                    <button class="search-form__button-submit" type="submit">検索</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
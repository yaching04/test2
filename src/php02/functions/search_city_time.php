<?php

function searchCityTime($city_name)
{
    require('config/cities.php');

    foreach ($cities as $city) {
        if ($city['name'] === $city_name) {
            $date_time = new DateTime('', new DateTimeZone($city['time_zone']));
            $week = ['日','月','火','水','木','金','土'];
            $time = $date_time->format('n/j'). " (".$week[$date_time->format('w')].") <".$date_time->format('H:i').">";
            $city['time'] = $time;

            //時差計算
            $now_tokyo = new DateTime('', new DateTimeZone('Asia/Tokyo'));
            $now_city = new DateTime('', new DateTimeZone($city['time_zone']));
            $diff_hours = ($now_city->getOffset() - $now_tokyo->getOffset()) / 3600;
            $sign = $diff_hours >= 0 ? '+' : '-';

            $hours = floor(abs($diff_hours));
            $minute = round((abs($diff_hours) - $hours) * 60);
        if ($hours == 0 && $minute == 0) {
            $city['diff'] = "なし";
        }else{
            $city['diff'] = $sign . $hours . "時間" . ($minute > 0 ? $minute . "分" : "");
        }

        //天気
        if (isset($city['lat']) && isset($city['lon'])) {
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$city['lat']}&longitude={$city['lon']}&current=temperature_2m,relative_humidity_2m,weather_code,precipitation_probability";
            $w = json_decode(file_get_contents($url), true) ?: [];

            $code = $w['current']['weather_code'] ?? 0;
            $temp = $w['current']['temperature_2m'] ?? '不明';
            $hum = $w['current']['relative_humidity_2m'] ?? '不明';
            $rain = $w['current']['precipitation_probability'] ?? '不明';

            $weather = [0=>'快晴',1=>'晴れ',2=>'一部曇り',3=>'曇り',45=>'霧',51=>'小雨',61=>'雨',71=>'雪',95=>'雷雨'][$code] ?? '不明';
            $city['weather'] = $weather;
            $city['temp'] = $temp;
            $city['hum'] = $hum;
            $city['rain'] = $rain;

        }

            return $city;
        }
    }
}

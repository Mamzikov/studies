<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 20.01.2016
 * Time: 12:12
 */

error_reporting(-1);
mb_internal_encoding('utf-8');

function circle ($phrase, $fromAngle, $radius, $height, $centerX, $centerY) {

    $result = '';
    $screen = array();
    for ($y = 0; $y < $height; $y++) {
        $screen[$y] = array_fill(0, $centerX * 2, ' ');
    }

    $phraseLength = mb_strlen($phrase);

    $ratio = 2.3;
    for ($i = 0, $char = 0; $i < $phraseLength; $i++, $char++) {
        $x = $centerX + round($radius * sin($fromAngle * M_PI / 180));
        $y = $centerY + round($radius * cos($fromAngle * M_PI / 180));
        $x = round(($x * $ratio) - ($centerX + $ratio * 5));
//        echo '$x: '.$x.', $y: '.$y.', $i = '.$i.PHP_EOL;
        $screen[$y][$x] = mb_substr($phrase, $char, 1);
        $fromAngle += 360 / $phraseLength;
    }

    foreach ($screen as $strings) {
        $result .= implode('', $strings).PHP_EOL;
    }

    return $result;

}

echo circle ('абвгдеёжзийклмнопрстуфхцчшщьъэюя', 0,13, 30, 40, 15);
echo circle ('абвгде', 0,5, 30, 40, 15);
echo circle ('абвгдеёжзийклмнопрстуфхцчшщьъэюяабвгдеёжзийклмнопрстуфхцчшщьъэюя', 90,10, 30, 40, 15);
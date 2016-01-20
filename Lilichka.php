<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 18.01.2016
 * Time: 13:18
 */
error_reporting(-1);
mb_internal_encoding('utf-8');

function verticalText ($text) {

    $result ='';
    $strings = preg_split('/[\r\n]|[\n]/i', $text, 0, PREG_SPLIT_NO_EMPTY);

    $i = 0;
    do {
        $haveSymbols = false;
        $line = '';
        foreach ($strings as $string) {
            if (mb_strlen($string) > $i) {
                $symbol = mb_substr($string, $i, 1);
                $haveSymbols = true;
            }
            else {
                $symbol = ' ';
            }

            $line .= $symbol . '|';
        }
        if ($haveSymbols == true)
            $result .= $line . PHP_EOL;
        $i++;
    }
    while ($haveSymbols == true);

    return $result;
}


echo verticalText(
    "Дым табачный воздух выел.
Комната -
глава в крученыховском аде.
Вспомни -
за этим окном
впервые
руки твои, исступлённый, гладил.
Сегодня сидишь вот,
сердце в железе.
День ещё -
выгонишь,
может быть, изругав.
В мутной передней долго не влезет
сломанная дрожью рука в рукав."
);
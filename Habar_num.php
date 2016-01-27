<?php
/**
 * Возвращает сумму прописью
 * @author runcore
 * @uses morph(...)
 */
include 'main_coiffured.php';
function num2str($num) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
        array ('триллион','триллиона', 'триллионов',0),
        array ('квадриллион','квадриллиона','квадриллионов', 0),
    );
    //

    list($rub,$kop) = explode('.',sprintf("%021.2f", floatval($num))); // TODO error function 'sprintf'

    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key

            $gender = $unit[$uk][3];


            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } //foreach
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));

}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}

/*function smallTest($num, $validResult) {
    $textNum = num2str($num);
    if ($textNum == $validResult ) {
        echo 'Ошибок нет! Цифра ('.$num.' рубля): '.$textNum.PHP_EOL;
    } else {
        echo 'Ошибка в преобразовании цифры '.$num.' в текст: '.$textNum.' (ожидается: '.$validResult.')'.PHP_EOL;
    }
}
function test() {
    smallTest('784','семьсот восемьдесят четыре рубля 00 копеек');
    smallTest('784546.11','семьсот восемьдесят четыре тысячи пятьсот сорок шесть рублей 11 копеек');
    smallTest('784,546.11','семьсот восемьдесят четыре тысячи пятьсот сорок шесть рублей 11 копеек');
    smallTest('784546321.01','семьсот восемьдесят четыре миллиона пятьсот сорок шесть тысяч триста двадцать один рубль 01 копейка');
    smallTest('784000321.22','семьсот восемьдесят четыре миллиона триста двадцать один рубль 22 копейки');
    smallTest('784000000','семьсот восемьдесят четыре миллиона рублей 00 копеек');
    smallTest('123651861321321651531232013515613133.11','сто двадцать три дециллиона шестьсот пятьдесят один нониллион восемьсот шестьдесят один октиллион триста двадцать один сеплиллион триста двадцать один секстиллион шестьсот пятьдесят один квинтиллион пятьсот тридцать один квадриллион двести тридцать два триллиона тринадцать миллиардов пятьсот пятнадцать миллионов шестьсот тринадцать тысяч сто тридцать три рубля 11 копеек');
    smallTest('13515631133','тринадцать миллиардов пятьсот пятнадцать миллионов шестьсот тридцать одна тысяча сто тридцать три рубля 00 копеек');
    smallTest('123456123651861321321651531232013515613133.11','Для цифр (123456)123651861321321651531232013515613133 не существует названий разрядов');
    smallTest('651861321321651531232013515613133.11','шестьсот пятьдесят один нониллион восемьсот шестьдесят один октиллион триста двадцать один сеплиллион триста двадцать один секстиллион шестьсот пятьдесят один квинтиллион пятьсот тридцать один квадриллион двести тридцать два триллиона тринадцать миллиардов пятьсот пятнадцать миллионов шестьсот тринадцать тысяч сто тридцать три рубля 11 копеек');
    smallTest('65186 1321321651531232013515613133.11','шестьсот пятьдесят один нониллион восемьсот шестьдесят один октиллион триста двадцать один сеплиллион триста двадцать один секстиллион шестьсот пятьдесят один квинтиллион пятьсот тридцать один квадриллион двести тридцать два триллиона тринадцать миллиардов пятьсот пятнадцать миллионов шестьсот тринадцать тысяч сто тридцать три рубля 11 копеек');
    smallTest('65186 13213216 5153123201 3515613133.11','шестьсот пятьдесят один нониллион восемьсот шестьдесят один октиллион триста двадцать один сеплиллион триста двадцать один секстиллион шестьсот пятьдесят один квинтиллион пятьсот тридцать один квадриллион двести тридцать два триллиона тринадцать миллиардов пятьсот пятнадцать миллионов шестьсот тринадцать тысяч сто тридцать три рубля 11 копеек');
    smallTest('33.11111','тридцать три рубля 11 копеек');
    smallTest('33.11511','тридцать три рубля 12 копеек');
    smallTest('33,1159865111','тридцать три рубля 12 копеек');
    smallTest('33/1159865111','тридцать три рубля 12 копеек');
    smallTest('0,1159865111','ноль рублей 12 копеек');
    smallTest('0,1149865111','ноль рублей 11 копеек');
    smallTest('0','ноль рублей 00 копеек');
}
*/
//test();


function comparisonOfWorkingTime()
{
    $t = -microtime(true);
    for ($q = 0; $q < 1000000; ++$q) {
        $res = num2str(784);
    }
    $t += microtime(true);
    echo $t, PHP_EOL;

    $t = -microtime(true);
    for ($q = 0; $q < 1000000; ++$q) {
        $res = smallNumberToText('784');
    }
    $t += microtime(true);
    echo $t, PHP_EOL;
}

comparisonOfWorkingTime();
exit;
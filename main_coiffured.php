<?php

// Staring straight up into the sky ... oh my my
error_reporting(-1);
mb_internal_encoding('utf-8');


/* Возвращает соответствующую числу форму слова: 1 рубль, 2 рубля, 5 рублей */
function inclineWord($number) {

    $endTen = substr($number, -1);
    $endHundred= substr($number, -2);

    if (($endTen == 1) and ($endHundred != 11) and ($endHundred != 12) and ($endHundred != 13) and ($endHundred != 14)) {
        $word = 'рубль';

    } elseif ((($endTen == 4) | ($endTen == 3) | ($endTen == 2)) and ($endHundred != 12) and ($endHundred != 13) and ($endHundred != 14)) {
        $word = 'рубля';

    } else {
        $word = 'рублей';
    }
    return $word;
}

/*
 *     Преобразует числа от 0 до 999 в текст. Параметр $isFemale равен нулю,
 *     если мы считаем число для мужского рода (один рубль),
 *     и 1 — для женского (одна тысяча)
 */

function smallNumberToText($number, $numberIsFemale = 0) {

    $spelling = array(
        0   =>  'ноль',                                     10  =>  'десять',       100 =>  'сто',
        1   =>  'один',         11  =>  'одиннадцать',      20  =>  'двадцать',     200 =>  'двести',
        2   =>  'два',          12  =>  'двенадцать',       30  =>  'тридцать',     300 =>  'триста',
        3   =>  'три',          13  =>  'тринадцать',       40  =>  'сорок',        400 =>  'четыреста',
        4   =>  'четыре',       14  =>  'четырнадцать',     50  =>  'пятьдесят',    500 =>  'пятьсот',
        5   =>  'пять',         15  =>  'пятнадцать',       60  =>  'шестьдесят',   600 =>  'шестьсот',
        6   =>  'шесть',        16  =>  'шестнадцать',      70  =>  'семьдесят',    700 =>  'семьсот',    
        7   =>  'семь',         17  =>  'семнадцать',       80  =>  'восемьдесят',   800 =>  'восемьсот',
        8   =>  'восемь',       18  =>  'восемнадцать',     90  =>  'девяносто',     900 =>  'девятьсот',
        9   =>  'девять',       19  =>  'девятнадцать'    
    );
    
    $femaleSpelling = array(
        1   =>  'одна',
        2   =>  'две'
    );

    if ($numberIsFemale == 1) {
        $spelling = array_replace($spelling, $femaleSpelling);
    }

    $hundreds = floor($number /100)*100;
    $tens = floor($number /10)*10 - $hundreds;
    $last = $number%10;

    if (($number > 99) and ($number <= 999) and ($number%100 !== 0)) {
        $word1 = $spelling[(int) $hundreds];

        if (($last == 0) | (($number%100  >= 1) and ($number%100  <= 19))) {
            $word3 = $spelling[$number%100];
            $textNum = $word1.' '.$word3;
        } else {
            $word2 = $spelling[(int) $tens];
            $word3 = $spelling[$last];
            $textNum = $word1.' '.$word2.' '.$word3;
        }

    } elseif (($number >= 20) and ($number <= 99) and ($last !== 0)) {
        $word1 = $spelling[(int) $tens];
        $word2 = $spelling[$last];
        $textNum = $word1.' '.$word2;
    } else {
        $word1 = $spelling[$number];
        $textNum = $word1;
    }


    return $textNum;
}

function bigNumberToText($number, $us_format = false) {

    $nameRanks = array (
        array ('min' => pow(10, 3), 'nameOne'=> 'тысяча', 'nameTwo' => 'тысячи', 'nameMore' => 'тысяч', 'isFemale' => 1),
        array ('min' => pow(10, 6), 'nameOne'=> 'миллион', 'nameTwo' => 'миллиона', 'nameMore' => 'миллионов', 'isFemale' => 0),
        array ('min' => pow(10, 9), 'nameOne' => 'миллиард', 'nameTwo' => 'миллиарда', 'nameMore' => 'миллиардов', 'isFemale' => 0),
        array ('min' => pow(10, 12), 'nameOne' => 'триллион', 'nameTwo' => 'триллиона', 'nameMore' => 'триллионов', 'isFemale' => 0),
        array ('min' => pow(10, 15), 'nameOne' => 'квадриллион', 'nameTwo' => 'квадриллиона', 'nameMore' => 'квадриллионов', 'isFemale' => 0),
        array ('min' => pow(10, 18), 'nameOne' => 'квинтиллион', 'nameTwo' => 'квинтиллиона', 'nameMore' => 'квинтиллионов', 'isFemale' => 0),
        array ('min' => pow(10, 21), 'nameOne' => 'секстиллион', 'nameTwo' => 'секстиллиона', 'nameMore' => 'секстиллиона', 'isFemale' => 0),
        array ('min' => pow(10, 24), 'nameOne' => 'сеплиллион', 'nameTwo' => 'септиллиона', 'nameMore' => 'септиллионов', 'isFemale' => 0),
        array ('min' => pow(10, 27), 'nameOne' => 'октиллион', 'nameTwo' => 'октиллиона', 'nameMore' => 'октиллионов', 'isFemale' => 0),
        array ('min' => pow(10, 30), 'nameOne' => 'нониллион', 'nameTwo' => 'нониллиона', 'nameMore' => 'нониллионов', 'isFemale' => 0),
        array ('min' => pow(10, 33), 'nameOne' => 'дециллион', 'nameTwo' => 'дециллиона', 'nameMore' => 'детиллионов', 'isFemale' => 0),
    );


    $template = ($us_format)?'/[\\._\\/:\'-]/i':'/[\\.,_\\/:\'-]/i';
    $partNumbers = preg_split($template, $number);
    $str_number = preg_replace('/[^0-9]/i', '',$partNumbers[0]);

    $result = '';

    $numberOfRanks = (count($nameRanks)+1)*3;

    if (strlen($str_number) > $numberOfRanks) {
        $result = 'Для цифр ('.substr($str_number, 0, strlen($str_number)-$numberOfRanks).')'.substr($str_number, -$numberOfRanks).' не существует названий разрядов';
    } else {

        $numberOfGroups = ceil(mb_strlen($str_number) / 3);

        $maxLen = $numberOfGroups * 3;
        $realLen = strlen($str_number);
        $diff = $maxLen - $realLen;

        $startLen = 3 - $diff;

        $start = 0;
        $len = $startLen;
        for ($groupNo = 1; $groupNo <= $numberOfGroups; $groupNo++) {

            //        // 3 456 789
            //        $start = 0; $len = 1;
            //        $start = 1; $len = 3;
            //        $start = 4; $len = 3;
            //
            //        // 23 456 789
            //        $start = 0; $len = 2;
            //        $start = 2; $len = 3;
            //        $start = 5; $len = 3;
            //
            //        // 123 456 789
            //        $start = 0; $len = 3;
            //        $start = 3; $len = 3;
            //        $start = 6; $len = 3;


            $groupNumber = (int)substr($str_number, $start, $len);
            $rankIndex = $numberOfGroups - $groupNo - 1;

            if (($rankIndex >= 0) and ($nameRanks[$rankIndex]['isFemale'] == 1)) {
                if (($groupNumber % 10 == 1) | ($groupNumber % 10 == 2)) {
                    $IsFemale = 1;
                } else {
                    $IsFemale = 0;
                }
            } else {
                $IsFemale = 0;
            }

            if (($groupNumber != 0) | (($groupNumber == 0) and ($groupNo == 1)))
                $result .= smallNumberToText($groupNumber, $IsFemale) . ' ';


            if (($rankIndex >= 0) and ($groupNumber != 0)) {
                if (($groupNumber % 10 == 1) and ($groupNumber % 100 !== 11)) {
                    $declensionName = 'nameOne';
                } elseif (($groupNumber % 10 >= 2) and ($groupNumber % 10 <= 4) and ($groupNumber % 100 !== 12) and ($groupNumber % 100 !== 13) and ($groupNumber % 100 !== 14)) {
                    $declensionName = 'nameTwo';
                } else {
                    $declensionName = 'nameMore';
                }
                $result .= $nameRanks[$rankIndex][$declensionName] . ' ';
            }


            $start += $len;
            $len = 3;
        }


        $result .= inclineWord($str_number) . ' ';

        if (count($partNumbers) > 1) {  // Проверка наличия копеек
            $kop = str_pad(((round((float) ('0.'.substr($partNumbers[1],0,3)),2))*100),2,'0',STR_PAD_LEFT);
        } else {
            $kop = 0;
        }

        if (($kop % 10 == 1) and ($kop % 100 !== 11)) {
            $nameCent = 'копейка';
        } elseif (($kop % 10 >= 2) and ($kop % 10 <= 4) and ($kop % 100 !== 12) and ($kop % 100 !== 13) and ($kop % 100 !== 14)) {
            $nameCent = 'копейки';
        } else {
            $nameCent = 'копеек';
        }

        $result .= $kop . ' ' . $nameCent;
    }
    return $result;
}
function smallTest($num, $validResult,$us_format = false) {
    $textNum = bigNumberToText($num, $us_format);
    if ($textNum == $validResult ) {
        echo 'Ошибок нет! Цифра ('.$num.' рубля): '.$textNum.PHP_EOL;
    } else {
        echo 'Ошибка в преобразовании цифры '.$num.' в текст: '.$textNum.' (ожидается: '.$validResult.')'.PHP_EOL;
    }
}
function test() {
    smallTest('784','семьсот восемьдесят четыре рубля 0 копеек');
    smallTest('784546.11','семьсот восемьдесят четыре тысячи пятьсот сорок шесть рублей 11 копеек');
    smallTest('784,546.11','семьсот восемьдесят четыре тысячи пятьсот сорок шесть рублей 11 копеек',true);
    smallTest('784546321.01','семьсот восемьдесят четыре миллиона пятьсот сорок шесть тысяч триста двадцать один рубль 01 копейка');
    smallTest('784000321.22','семьсот восемьдесят четыре миллиона триста двадцать один рубль 22 копейки');
    smallTest('784000000','семьсот восемьдесят четыре миллиона рублей 0 копеек');
    smallTest('123651861321321651531232013515613133.11','сто двадцать три дециллиона шестьсот пятьдесят один нониллион восемьсот шестьдесят один октиллион триста двадцать один сеплиллион триста двадцать один секстиллион шестьсот пятьдесят один квинтиллион пятьсот тридцать один квадриллион двести тридцать два триллиона тринадцать миллиардов пятьсот пятнадцать миллионов шестьсот тринадцать тысяч сто тридцать три рубля 11 копеек');
    smallTest('13515631133','тринадцать миллиардов пятьсот пятнадцать миллионов шестьсот тридцать одна тысяча сто тридцать три рубля 0 копеек');
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
    smallTest('0','ноль рублей 0 копеек');
}

//test();





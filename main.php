<?php

// Staring straight up into the sky ... oh my my
error_reporting(-1);
mb_internal_encoding('utf-8');


/* Возвращает соответствующую числу форму слова: 1 рубль, 2 рубля, 5 рублей */
function inclineWord($number) {
   
    if (($number%10 == 1) && ($number%100 !== 11) && ($number%100 !== 12) && ($number%100 !== 13) && ($number%100 !== 14)) {
        $word = 'рубль';
    } elseif (($number%10 >=2) && ($number%10 <= 4) && ($number%100 !== 12) && ($number%100 !== 13) && ($number%100 !== 14)) {
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
function smallNumberToText($number, $thousandsIsFemale = 0) {

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


    if ($thousandsIsFemale == 1) {
        $spelling = array_replace($spelling, $femaleSpelling);
    }


     if (($number > 99) && ($number <= 999) && ($number%100 !== 0)) {
        $word1 = $spelling[(int) ((floor($number /100))*100)];
        
        if (($number%10 == 0) | (($number%100  >= 1) && ($number%100  <= 19))) {
            $word3 = $spelling[$number%100];
            $textNum = $word1.' '.$word3;
        } else {
            $word2 = $spelling[(int) (((floor($number /10))*10)-((floor($number /100))*100))];
            $word3 = $spelling[$number%10];
            $textNum = $word1.' '.$word2.' '.$word3;
        }
        
     } elseif (($number >= 20) && ($number <= 99) && ($number%10 !== 0)) {
        $word1 = $spelling[(int) ((floor($number /10)) * 10)];
        $word2 = $spelling[$number%10];
        $textNum = $word1.' '.$word2;
     } else {
        $word1 = $spelling[$number];
        $textNum = $word1;
     } 


    return $textNum;
}

function bigNumberToText($number) {

    $millions = floor($number/1000000);
    $thousands = floor($number/1000)%1000;
    $small = $number%1000;

    if (($millions%10 == 1) && ($millions%100 !== 11)) {
        $textMillion = 'миллион';
    }   elseif (($millions%10 >= 2) && ($millions%10 <=4) && ($millions%100 !== 12) && ($millions%100 !== 13) && ($millions%100 !== 14)) {
        $textMillion = 'миллиона';
    }   else {
        $textMillion = 'миллионов';
    }

    if (($thousands%10 ==1) && ($thousands%100 !==11)) {
        $textThousand = 'тысяча';
    }   elseif (($thousands%10 >= 2) && ($thousands%10 <=4) && ($thousands%100 !==12) && ($thousands%100 !==13) && ($thousands%100 !==14)) {
        $textThousand = 'тысячи';
    }   else {
        $textThousand = 'тысяч';
    }

    if ($thousands==0) {
        $thousandsIsFemale = null;
    }
    elseif (($thousands%10 == 1) | ($thousands%10 == 2)) {
        $thousandsIsFemale = 1;
    } else {
        $thousandsIsFemale = 0;
    }

    if (($millions == 0) && ($thousands !=0) && ($small !=0)) {
        $result = smallNumberToText($thousands, $thousandsIsFemale).' '. $textThousand.' '.smallNumberToText($small, 0);

    } elseif (($thousands == 0) && ($millions !=0) && ($small !=0)) {
        $result = smallNumberToText($millions).' '.$textMillion.' '.smallNumberToText($small);

    } elseif (($small == 0) && ($millions !=0) && ($thousands !=0)) {
        $result = smallNumberToText($millions).' '.$textMillion.' '.smallNumberToText($thousands).' '. $textThousand;

    } elseif (($millions == 0) && ($thousands == 0) && ($small !=0)) {
        $result = smallNumberToText($small);

    } elseif (($small == 0) && ($thousands == 0) && ($millions !=0)) {
        $result = smallNumberToText($millions).' '.$textMillion;

    } elseif (($small == 0) && ($millions == 0) && ($thousands !=0)) {
        $result = smallNumberToText($thousands, $thousandsIsFemale).' '. $textThousand;

    } elseif (($millions == 0) && ($thousands == 0) && ($small == 0)) {
        $result = smallNumberToText($small);

    } else {
        $result = smallNumberToText($millions).' '.$textMillion.' '.smallNumberToText($thousands, $thousandsIsFemale).' '. $textThousand.' '.smallNumberToText($small);

    }

    return $result;
}



function numberToText($number) {

$result = bigNumberToText($number).' '.inclineWord($number);
  return $result;
}

/* Вызовем функцию несколько раз */
$amount1 = mt_rand(1,99999999);
$text1 = numberToText($amount1);

echo "На вашем счету ({$amount1}) {$text1}\n";

$amount2 = mt_rand(1,99999999);
$text2 = numberToText($amount2);

echo "На вашем счету ({$amount2}) {$text2}\n";

$amount3 = mt_rand(1,99999999);
$text3 = numberToText($amount3);

echo "На вашем счету ({$amount3}) {$text3}\n";

$amount4 = mt_rand(1,99999999);
$text4 = numberToText($amount4);

echo "На вашем счету ({$amount4}) {$text4}\n";
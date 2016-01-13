<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 12.01.2016
 * Time: 10:06
 */
error_reporting(-1);
mb_internal_encoding('utf-8');


function textNumber ($amount) {
    include 'main_coiffured.php';
    return bigNumberToText($amount);
}

function numberOfBills ($amount, $bills) {

    if ($amount%100 !=0) {
        echo 'Сумма '.$amount.' не кратна 100, введите корректную сумму';
        exit;
    }
    $result = 'Доступны к получению купюры: ';
    $restSum = $amount;

    foreach ($bills as $nominal => $restBills ) {
        $sumBills =0;
        while (($restSum >= $nominal) and ($sumBills <=$restBills)) {
            $restSum = $restSum - $nominal;
            $restBills--;
            $sumBills++;
        }
        if ($sumBills !=0) $result .= $sumBills.'шт. по '.$nominal.'руб. ';
    }

    return $result;
}



function outputTextOnMonitor ($amount, $bills) {
    echo 'Сумма: ('.$amount.')'.textNumber($amount).PHP_EOL;
    echo numberOfBills ($amount, $bills).PHP_EOL;
}

outputTextOnMonitor (53400, array(5000 => 20, 1000 => 6, 500 => 15, 100 => 40,));

function testNumber($amount, $bills, $validResult) {
    if (outputTextOnMonitor($amount, $bills) == $validResult) {
        echo 'Ошибок нет!'.PHP_EOL.outputTextOnMonitor ($amount, $bills).PHP_EOL;
    } else {
        echo 'Ошибка! Текст на экране:'.PHP_EOL.outputTextOnMonitor ($amount, $bills).PHP_EOL.'Ожидается:'.PHP_EOL.$validResult.PHP_EOL;
    }
}

//testNumber (53400, array(5000 => 15, 1000 => 6, 500 => 15, 100 => 40,), 'Сумма: (53400) пятьдесят три тысячи четыреста рублей 0 копеекДоступны к получению купюры: 10шт. по 5000руб. 3шт. по 1000руб. 4шт. по 100руб.');
<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 12.01.2016
 * Time: 10:06
 */
error_reporting(-1);
 mb_internal_encoding('utf-8');

include 'main_coiffured.php';

function textNumber($amount)
{
    return bigNumberToText($amount);
}

function numberOfBills($amount, $bills)
{

    if ($amount % 100 != 0) {
        $result = 'Сумма ' . $amount . ' не кратна 100, введите корректную сумму';
    } elseif ($amount == 0) {
        $result = 'На счете нет доступной для сния суммы';
    } else {

        $result = (array_sum($bills) != 0) ? 'Доступны к получению купюры: ' : '';

        $restSum = $amount;

        foreach ($bills as $nominal => $restBills) {
            $sumBills = 0;
            while (($restSum >= $nominal) and ($restBills > 0)) {

                $restSum = $restSum - $nominal;
                $restBills--;
                $sumBills++;

            }

            if ($sumBills != 0) $result .= $sumBills . 'шт. по ' . $nominal . 'руб. ';
        }
        if ($restSum != 0) $result .= 'Остаток ' . $restSum . 'руб. не может быть выдан из-за отстутвия в банкомате необходимого количества банкнот';

    }
    return $result;
}

function numberOfBills2($amount, $bills) {

    if ($amount % 100 != 0) {
        $result = 'Сумма ' . $amount . ' не кратна 100, введите корректную сумму';
    } elseif ($amount == 0) {
        $result = 'На счете нет доступной для сния суммы';
    } else {

        $result = (array_sum($bills) != 0) ? 'Доступны к получению купюры: ' : '';

        $restSum = $amount;

        foreach ($bills as $nominal => $restBills) {

            $totalBills = floor($restSum / $nominal);

            if ($totalBills >= $restBills) {
                $sumBills = $restBills;
            } else {
                $sumBills = $totalBills;
            }
            $restSum = $restSum - $nominal * $sumBills;

            if ($sumBills != 0)
                $result .= $sumBills . 'шт. по ' . $nominal . 'руб. ';
        }
        if ($restSum != 0)
            $result .= 'Остаток ' . $restSum . 'руб. не может быть выдан из-за отстутвия в банкомате необходимого количества банкнот';
    }
    return $result;
}


function comparisonOfWorkingTime()
{
    $t = -microtime(true);
    for ($q = 0; $q < 100000; ++$q) {
        $res = numberOfBills2(50232300, array(5000 => 1897, 1000 => 222, 500 => 0, 100 => 500000,));
    }
    $t += microtime(true);
    echo $t, PHP_EOL;

    $t = -microtime(true);
    for ($q = 0; $q < 100000; ++$q) {
        $res = numberOfBills2(50232300, array(5000 => 0, 1000 => 0, 500 => 0, 100 => 500000,));
    }
    $t += microtime(true);
    echo $t, PHP_EOL;
}

//comparisonOfWorkingTime();
//exit;


function outputTextOnMonitor($amount, $bills)
{
    echo 'Сумма: (' . $amount . ')' . textNumber($amount) . PHP_EOL;
    echo numberOfBills($amount, $bills) . PHP_EOL;
}


function testNumber($amount, $bills, $validResult)
{
    if (numberOfBills2($amount, $bills) == $validResult) {
        echo 'Ошибок нет! ' . numberOfBills2($amount, $bills) . PHP_EOL;
    } else {
        echo 'Ошибка! Текст на экране:' . PHP_EOL . numberOfBills2($amount, $bills) . PHP_EOL . 'Ожидается:' . PHP_EOL . $validResult . PHP_EOL;
    }
}

testNumber(53400, array(5000 => 15, 1000 => 6, 500 => 15, 100 => 40,), 'Доступны к получению купюры: 10шт. по 5000руб. 3шт. по 1000руб. 4шт. по 100руб. ');
testNumber(53400, array(5000 => 0, 1000 => 6, 500 => 15, 100 => 400,), 'Доступны к получению купюры: 6шт. по 1000руб. 15шт. по 500руб. 399шт. по 100руб. ');
testNumber(53400, array(5000 => 15, 1000 => 6, 500 => 15, 100 => 0,), 'Доступны к получению купюры: 10шт. по 5000руб. 3шт. по 1000руб. Остаток 400руб. не может быть выдан из-за отстутвия в банкомате необходимого количества банкнот');
testNumber(53400, array(5000 => 15, 1000 => 0, 500 => 15, 100 => 0,), 'Доступны к получению купюры: 10шт. по 5000руб. 6шт. по 500руб. Остаток 400руб. не может быть выдан из-за отстутвия в банкомате необходимого количества банкнот');
testNumber(53400, array(5000 => 15, 1000 => 0, 500 => 0, 100 => 0,), 'Доступны к получению купюры: 10шт. по 5000руб. Остаток 3400руб. не может быть выдан из-за отстутвия в банкомате необходимого количества банкнот');
testNumber(53400, array(5000 => 0, 1000 => 0, 500 => 0, 100 => 0,), 'Остаток 53400руб. не может быть выдан из-за отстутвия в банкомате необходимого количества банкнот');
testNumber(0, array(5000 => 0, 1000 => 2, 500 => 0, 100 => 0,), 'На счете нет доступной для сния суммы');
testNumber(3410, array(5000 => 0, 1000 => 2, 500 => 0, 100 => 0,), 'Сумма 3410 не кратна 100, введите корректную сумму');

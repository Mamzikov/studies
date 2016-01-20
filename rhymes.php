<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 19.01.2016
 * Time: 9:42
 */
error_reporting(-1);


function rhymes($total, $skip) {

    $participants = array_combine(range(1, $total), range(1, $total));

    $residue = count($participants);
    $start = $skip;

if ($skip !=1) {
    while ($residue >= $skip) {

        $prevCount = count($participants);
        for ($i = $start; $i <= $residue; $i += $skip) {
            unset($participants[$i]);
        }
        $residue = count($participants);
        $participants = array_combine(range(1, $residue), $participants);
        $start = $skip - ($prevCount - ($i - $skip));

    }
    $result = 'Выигрышные места: ' . implode(',', $participants);
} else {
    $result = 'Выигрышные места: ' . end($participants);
}

    return $result;
}

function test($total, $skip, $validResult) {
    if ((rhymes($total, $skip) == $validResult)) {
        echo 'Ошибок нет! '.$validResult.PHP_EOL;
    } else {
        echo 'Ошибка! '.rhymes($total, $skip).' Ожидается: '.$validResult.PHP_EOL;
    }
}

test (10,5, 'Выигрышные места: 1,3,4,7');
test (30,5, 'Выигрышные места: 3,4,14,27');
test (10,3, 'Выигрышные места: 4,10');
test (2,3, 'Выигрышные места: 1,2');
test (1,3, 'Выигрышные места: 1');
test (3,3, 'Выигрышные места: 1,2');
test (3,1, 'Выигрышные места: 3');
test (3,2, 'Выигрышные места: 3');
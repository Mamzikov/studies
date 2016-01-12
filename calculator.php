<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 11.01.2016
 * Time: 10:49
 */
error_reporting(-1);
mb_internal_encoding('utf-8');


function charInAction ($num, $num2, $char) {

    switch ($char) {
        case '+':
            $result = $num + $num2;
            break;
        case '-':
            $result = $num - $num2;
            break;
        case '*':
            $result = $num * $num2;
            break;
        case '/':
            $result = $num / $num2;
            break;
        default:
            $result = null;
    }

//    if ($char == '+') $result = $num + $num2;
//    if ($char == '-') $result = $num - $num2;
//    if ($char == '*') $result = $num * $num2;
//    if ($char == '/') $result = $num / $num2;

    return $result;
}


function calculator($inputText) {


    $numbers = preg_split('/[-\\+*=\\/]/i', str_replace(',', '.', $inputText), 0, PREG_SPLIT_NO_EMPTY);
    $operators = preg_split('/[0-9^,^\\.]/i', $inputText, 0, PREG_SPLIT_NO_EMPTY);

   $result = charInAction($numbers[0], $numbers[0+1], $operators[0]);

    for ($i=1; $i<count($operators)-1; $i++) {
        if (is_null($result = charInAction($result, $numbers[$i+1], $operators[$i]))) {
            echo 'Ошибка!'; die();
        }

    }

    return round($result, 2);

}



function test($inputString, $validResult) {
    if ((calculator($inputString) == $validResult)) {
        echo 'Ошибок нет! '.$inputString.' '.$validResult.PHP_EOL;
    } else {
        echo 'Ошибка '.$inputString.' '.calculator($inputString).' Ожидается: '.$validResult.PHP_EOL;
    }
}

test ('45+653-89*6/5=', '730.8');
test ('45/65*56+65-47=', '56.77');
test ('1.5+653=', '654.5');
test ('2000,5*2/2=', '2000.5');
test ('2.25*2=', '4.5');
test ('2.251234*2=', '4.5');
test ('2.25987*2=', '4.52');
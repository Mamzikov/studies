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


    return $result;
}


function calculator($inputText) {

    $inputLength = mb_strlen($inputText);
    $number = '';
    $result = null;
    $operator = '';


    for ($i=0; $i<$inputLength; $i++) {

        $char = mb_substr($inputText, $i, 1);
        if (($char != '+') and ($char != '-') and ($char != '*') and ($char != '/') and ($char != '=')) {
            $number .=  str_replace(',','.',$char);
        } else {
            if (is_null($result)) {
                $result = $number;
                $number = '';
            }
            else {
//                echo 'calculate '.$result.' '.$operator.' '.$number.' = ';
                $result = charInAction($result, $number, $operator);
                $number = '';
            }
            $operator = $char;
        }


    }

    return round($result, 2);
}

//echo calculator('2.25+2,5=');
//exit;

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
test ('2.25+2,5=', '4.75');
<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 21.01.2016
 * Time: 21:28
 */
error_reporting(-1);
/* http://d...content-available-to-author-only...2.net/ */

define('SUBWAY', 'едешь на метро');
define('FOOT', 'идешь пешком');
define('BUS', 'едешь на автобусе');


//    $transportName = array(
//        SUBWAY  =>  'едешь на метро',
//        FOOT    =>  'идешь пешком',
//        BUS     =>  'едешь на автобусе'
//    );



    $pointNames = array(
        'pet'   =>  'ст. м. Петроградская',
        'chk'   =>  'ст. м. Чкаловская',
        'gor'   =>  'ст. м. Горьковская',
        'spo'   =>  'ст. м. Спортивная',
        'vas'   =>  'ст. м. Василеостровская',
        'kre'   =>  'Петропавловская крепость',
        'let'   =>  'Летний сад',
        'dvo'   =>  'Дворцовая площадь',
        'isa'   =>  'Исакиевский собор',
        'nov'   =>  'Новая Голландия',
        'ras'   =>  'Дом Раскольникова',
        'gos'   =>  'Гостиный Двор',
        'sen'   =>  'Сенная Площадь',
        'vla'   =>  'ст. м. Владимирская',
        'vit'   =>  'Витебский вокзал',
        'teh'   =>  'Технологический Институт'
    );


    $paths = array(
        'pet'   =>  array(
            'chk'   =>  array('time'=>10, 'by'=>BUS),
            'gor'   =>  array('time'=>3, 'by'=>SUBWAY),
        ),

        'chk'   =>  array(
            'pet'   =>  array('time'=>10, 'by'=>BUS),
            'spo'   =>  array('time'=>3, 'by'=>SUBWAY)
        ),

        'gor'   =>  array(
            'pet'   =>  array('time'=>3, 'by'=>BUS),
            'kre'   =>  array('time'=>5, 'by'=>FOOT),
            'gos'   =>  array('time'=>6, 'by'=>SUBWAY)
        ),

        'spo'   =>  array(
            'chk'   =>  array('time'=>3, 'by'=>SUBWAY),
            'vas'   =>  array('time'=>10, 'by'=>BUS),
            'sen'   =>  array('time'=>7, 'by'=>SUBWAY)
        ),

        'vas'   =>  array(
            'spo'   =>  array('time'=>10, 'by'=>BUS),
            'gos'   =>  array('time'=>7, 'by'=>SUBWAY),
            'nov'   =>  array('time'=>11, 'by'=>FOOT)
        ),

        'kre'   =>  array(
            'gor'   =>  array('time'=>5, 'by'=>FOOT)
        ),

        'let'   =>  array(
            'dvo'   =>  array('time'=>6, 'by'=>FOOT),
            'gos'   =>  array('time'=>7, 'by'=>FOOT)
        ),

        'dvo'   =>  array(
            'isa'   =>  array('time'=>6,'by'=> FOOT),
            'gos'   =>  array('time'=>6, 'by'=>FOOT),
            'let'   =>  array('time'=>6, 'by'=>FOOT)
        ),

        'isa'   =>  array(
            'dvo'   =>  array('time'=>6, 'by'=>FOOT),
            'nov'   =>  array('time'=>5, 'by'=>FOOT)
        ),

        'nov'   =>  array(
            'vas'   =>  array('time'=>11, 'by'=>FOOT),
            'isa'   =>  array('time'=>5, 'by'=>FOOT),
            'ras'   =>  array('time'=>7, 'by'=>BUS)
        ),

        'ras'   =>  array(
            'nov'   =>  array('time'=>7, 'by'=>BUS),
            'sen'   =>  array('time'=>3, 'by'=>FOOT)
        ),

        'gos'   =>  array(
            'vas'   =>  array('time'=>7, 'by'=>SUBWAY),
            'sen'   =>  array('time'=>3, 'by'=>SUBWAY),
            'dvo'   =>  array('time'=>6,'by'=> FOOT),
            'gor'   =>  array('time'=>6, 'by'=>SUBWAY),
            'let'   =>  array('time'=>7, 'by'=>FOOT),
            'vla'   =>  array('time'=>7, 'by'=>FOOT)
        ),

        'sen'   =>  array(
            'ras'   =>  array('time'=>3, 'by'=>FOOT),
            'spo'   =>  array('time'=>7, 'by'=>SUBWAY),
            'gos'   =>  array('time'=>3, 'by'=>SUBWAY),
            'vla'   =>  array('time'=>4,'by'=> SUBWAY),
            'vit'   =>  array('time'=>2, 'by'=>SUBWAY),
            'teh'   =>  array('time'=>3, 'by'=>SUBWAY)
        ),

        'vla'   =>  array(
            'sen'   =>  array('time'=>4, 'by'=>SUBWAY),
            'gos'   =>  array('time'=>7, 'by'=>FOOT),
            'vit'   =>  array('time'=>3,'by'=> SUBWAY)
        ),

        'vit'   =>  array(
            'sen'   =>  array('time'=>2, 'by'=>SUBWAY),
            'teh'   =>  array('time'=>2, 'by'=>SUBWAY),
            'vla'   =>  array('time'=>2, 'by'=>SUBWAY)
        ),

        'teh'   =>  array(
            'sen'   =>  array('time'=>3, 'by'=>SUBWAY),
            'vit'   =>  array('time'=>2, 'by'=>SUBWAY)
        )
    );


    /**
     * Находит все маршруты от точки $startPoint до $endPoint и выбирает опимальный по времени из них
     *
     * @param string $startPoint  Начальная точка
     * @param string $endPoint Конечная точка
     * @param string $passedPath Пройденый путь
     * @param int $accTime Аккумулированное время на пройденном пути
     * @return array  Массив маршрутов
     */
    function navigator ($startPoint, $endPoint, $passedPath = '', $accTime = 0) {

        global $paths;

        $res = array();

        if (isset($paths[$startPoint])) {
            $path = $paths[$startPoint];

            foreach ($path as $point=>$weight) {
                if ($point == $endPoint) {
                    $res[$passedPath.'|'.$startPoint.'|'.$point] = $accTime + $paths[$startPoint][$point]['time'];
                }
                elseif ((isset($paths[$point])) and (strpos($passedPath, $point) === false)) {
                    foreach (navigator($point, $endPoint, $passedPath.'|'.$startPoint, $accTime + $paths[$startPoint][$point]['time']) as $key=>$item) {
                        $res[$key] = $item;
                    }
                }
            }
            if (count($res)) {
                $tmpRes = array();
                foreach (array_keys($res, min($res)) as $item)
                    $tmpRes[$item] = $res[$item];

                $res = $tmpRes;
            }
        }
        return $res;
    }


    /**
     * Записывает массив маршрутов в читаемый вид с указанием вида транспорта и времени от точки до точки
     * @param string $startPoint начальная точка
     * @param string $endPoint конечная точка
     * @return string Строка с описанием сколько и на чем ехать
     * @numRout int  количество маршрутов с одинаковым временем
     */

    function textRoute ($startPoint, $endPoint) {
        global $pointNames;
        global $paths;
        $result = array();

        $numRout = 0;
        foreach (navigator($startPoint, $endPoint) as $route => $time) {
            $points = preg_split('/\\|/i', $route, 0, PREG_SPLIT_NO_EMPTY);

            if ($numRout > 0) {
                $result[] = 'Альтернативный путь:';
            }
            $result[] = 'Начальная точка: "'.$pointNames[$startPoint].'"';

            for ($i=0; $i<count($points)-1; $i++ ) {
                $result[] = 'Из неё '.$paths[$points[$i]][$points[$i+1]]['by'].' до точки "'.$pointNames[$points[$i+1]].'" '.$paths[$points[$i]][$points[$i+1]]['time'].' мин.';
            }

            $result[] = 'В итоге попадёшь в точку "'.$pointNames[$endPoint].'" за '.$time.' мин. Приятной поездки!';

            $numRout++;
        }

       return $result;
    }

echo implode(PHP_EOL,textRoute ('pet', 'nov')).PHP_EOL;
echo '--------------------------'.PHP_EOL;
echo implode(PHP_EOL,textRoute ('sen', 'vla')).PHP_EOL;
echo '--------------------------'.PHP_EOL;
echo implode(PHP_EOL,textRoute ('chk', 'vla')).PHP_EOL;
echo '--------------------------'.PHP_EOL;
echo implode(PHP_EOL, textRoute ('vla', 'let')).PHP_EOL;


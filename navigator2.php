<?php


    $uz = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','Z'
    );

    $sv = array(
        'A' => array('B'=>1,'C'=>2,'D'=>3, 'M'=>4),
        'B' => array('E'=>2,'F'=>4, 'G'=> 5, 'H'=>2),
        'C' => array('B'=>1,'H'=>2, 'I'=>3, 'J'=>1),
        'D' => array('J'=>4, 'K'=>2, 'L'=>5),
        'E' => array('N'=>2, 'O'=>3),
        'F' => array('P'=>4),
        'I' => array('Q'=>3, 'R'=>4),
        'J' => array('Q'=>2),
        'L' => array('R'=>1, 'S'=>3,'Z'=>3),
        'Z' => array('D'=>4),
        'P' => array('L'=>2)
    );

    /**
     * Находит все маршруты от точки $start до $finish
     *
     * @param string $start  Начальная точка
     * @param string $finish Конечная точка
     * @param string $passedPath Пройденый путь
     * @return array  Массив маршрутов
     */
    function findPath($start, $finish, $passedPath = '') {
        global $sv;
        $res = array();
        if (isset($sv[$start])) {
            $path = $sv[$start];

            foreach ($path as $point=>$weight) {
                if ($point == $finish) {
                    $res[] = $passedPath.$start.$point;
                }
                elseif ((isset($sv[$point])) and (strpos($passedPath, $point) === false)) {
                    foreach (findPath($point, $finish, $passedPath.$start) as $item)
                        $res[] = $item;
                }
            }
        }

        return $res;
    }

    /**
     * Поиск оптимального пути
     * @param string $start начальная точка
     * @param string $finish конечная точка
     * @return array оптимальные пути (несколько если одинаковый вес)
     */
    function findOptimalPath($start, $finish) {
        global $sv;
        $paths = findPath($start, $finish);
        $allTime = array();
        foreach ($paths as $path) {
            $time = 0;
            for ($i=0; $i<strlen($path)-1; $i++) {
                $x = substr($path, $i,1);
                $y = substr($path, $i+1, 1);
                $time += $sv[$x][$y];
            }
            $allTime[$path] = $time;
        }
        $res = array_keys($allTime, min($allTime));
        return $res;
    }

/**
 * Поиск возможных маршрутов и выбор из них оптимального по времени
 * @param string $start начальная точка
 * @param string $finish конечная точка
 * @param string $passedPath Пройденный путь
 * @param int $accTime Аккумулированное время на пройденном пути
 * @return array оптимальные пути (несколько если одинаковый вес)
 */
    function findOptimalPathInc($start, $finish, $passedPath = '', $accTime = 0) {
        global $sv;
        $res = array();
        if (isset($sv[$start])) {
            $path = $sv[$start];

            foreach ($path as $point=>$weight) {
                if ($point == $finish) {
                    $res[$passedPath.$start.$point] = $accTime + $sv[$start][$point];
                }
                elseif ((isset($sv[$point])) and (strpos($passedPath, $point) === false)) {
                    foreach (findOptimalPathInc($point, $finish, $passedPath.$start, $accTime + $sv[$start][$point]) as $key=>$item) {
                        $res[$key] = $item;
                    }
                }
            }
            // $res = array of paths with lengths
            if (count($res)) {
                $tmpRes = array();
                foreach (array_keys($res, min($res)) as $item)
                    $tmpRes[$item] = $res[$item];

                $res = $tmpRes;
            }
        }


        return $res;
    }

//print_r(findOptimalPathInc('A','H'));
//exit;

    function myAssertArray($a, $b, $message = null) {
        if (serialize($a) == serialize($b))
            echo 'OK'.PHP_EOL;
        else
            echo 'ERROR'.(is_null($message)?':':' '.$message.':').' '.(count($a)?implode(',',$a):'array()').' <> '.(count($b)?implode(',',$b):'array()').PHP_EOL;
    }

    function testFindPath() {
        myAssertArray(findPath('A','H'), array('ABH','ACBH','ACH'), 'A -> H');
        myAssertArray(findPath('A','I'), array('ACI'), 'A -> I');
        myAssertArray(findPath('A','J'), array('ABFPLZDJ','ACBFPLZDJ','ACJ', 'ADJ'),'A -> J');
//        myAssert(findPath('A','J'), array('ADJ', 'ACJ'));
        myAssertArray(findPath('A','B'), array('AB','ACB'),' A -> B');
        myAssertArray(findPath('C','E'), array('CBE'),'C -> E');
        myAssertArray(findPath('A','M'), array('AM'),' A -> M');
        myAssertArray(findPath('B','M'), array(),'B -> M');
        myAssertArray(findPath('M','K'), array(),'M -> K');
        myAssertArray(findPath('A','Q'), array('ABFPLZDJQ','ACBFPLZDJQ','ACIQ','ACJQ','ADJQ'),'A -> Q');
        myAssertArray(findPath('A','R'), array('ABFPLR','ACBFPLR','ACIR','ADLR'),' A -> R');
        myAssertArray(findPath('C','Q'), array('CBFPLZDJQ','CIQ','CJQ'),'C -> Q');
        myAssertArray(findPath('B','D'), array('BFPLZD'),'B -> D');
    }

    function testFindOptimalPath() {
        myAssertArray(findOptimalPath('A','J'), array('ACJ'));
        myAssertArray(findOptimalPath('A','Q'), array('ACJQ'));
        myAssertArray(findOptimalPath('A','R'), array('ACIR','ADLR'));
        myAssertArray(findOptimalPath('C','Q'), array('CJQ'));
        myAssertArray(findOptimalPath('A','B'), array('AB'));
    }

    function testFindOptimalPathInc() {
        myAssertArray(findOptimalPathInc('A','J'), array('ACJ'));
        myAssertArray(findOptimalPathInc('A','Q'), array('ACJQ'));
        myAssertArray(findOptimalPathInc('A','R'), array('ACIR','ADLR'));
        myAssertArray(findOptimalPathInc('C','Q'), array('CJQ'));
        myAssertArray(findOptimalPathInc('A','O'), array('ABEO'));
        myAssertArray(findOptimalPathInc('A','B'), array('AB'));
    }



//    echo serialize(array('B'=>1,'C'=>2,'D'=>3)).PHP_EOL;
//    echo serialize(array('C'=>2,'D'=>3,'B'=>1)).PHP_EOL;
echo 'testFindPath'.PHP_EOL;
    testFindPath();
echo 'testFindOptimalPath'.PHP_EOL;
    testFindOptimalPath();
echo 'testFindOptimalPathInc'.PHP_EOL;
    testFindOptimalPathInc();
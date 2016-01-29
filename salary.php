<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 27.01.2016
 * Time: 15:45
 */


error_reporting(-1);
mb_internal_encoding('utf-8');

class Employee {
    public $name; // имя сотрудника
    public $rate; // часовая ставка
    private $hours = array(); // кол-во часов отработанных за недели
    const HOURS_PER_WEEK = 40; // норма часов в неделю

    public function __construct ($name, $rate) {
        $this->name = $name;
        $this->rate = $rate;
    }

    public function setHours($hours) {
        if (!is_array($hours))
            die('$hours is not array!');
        $this->hours = $hours;
    }

    public function getHours() {
        return $this->hours;
    }

    public function getNormalHours() { // сумма часов без переработки
        $normalHours = 0;
        foreach ($this->hours as $weekHours) {
            $normalHours += ($weekHours<=self::HOURS_PER_WEEK) ? $weekHours : self::HOURS_PER_WEEK;
        }
        return $normalHours;
    }
    public function getOvertimeHours() { // сумма часов переработки
        $overtimeHours = 0;
        foreach ($this->hours as $weekHours) {
            $overtimeHours += ($weekHours<=self::HOURS_PER_WEEK) ? 0 : $weekHours-self::HOURS_PER_WEEK;
        }
        return $overtimeHours;
    }
    public function getTotalHoursWorked() { // сумма всех отработанных часов
        $totalWorkedHours = $this->getNormalHours() + $this->getOvertimeHours();
        return $totalWorkedHours;
    }
    public function getSalary() { // зарплата за все отработанные часы
        $salary =  ($this->getNormalHours()*$this->rate)+($this->getOvertimeHours()*($this->rate*2));
        return $salary;
    }

}

class Employees {
    private $items = array();

    /**
     * @param Employee $employee
     */
    public function add(Employee $employee) {
        $this->items[] = $employee;
    }

    /**
     * @return array of Employee
     */
    public function getList() {
        return $this->items;
    }

    public function getOverallSalary() {
        $res = 0;
        foreach ($this->items as $employee)
            $res += $employee->getSalary();

        return $res;
    }

    public function getOverallHours() {
        $res = 0;
        foreach ($this->items as $employee)
            $res += $employee->getTotalHoursWorked();

        return $res;
    }

    public function getOverallOvertime() {
        $res = 0;
        foreach ($this->items as $employee)
            $res += $employee->getOvertimeHours();

        return $res;
    }

    /**
     * @param string $string Исходная строка
     * @param int $length Необходимая длина строки
     * @param string $side С какой стороны необходимы пробелы
     * @return string Строка дополненная пробелами до необходимой длины
     */
    public function indent ($string, $length, $side) {
        if (mb_strlen($string)>=$length)
            die('Ошибка! Ширина столбца меньше кол-ва символов строки. Минимальная ширина столбца: '.(mb_strlen($string)+1).PHP_EOL);
        $numSpaces = $length-mb_strlen($string);
        $indent = str_repeat(' ', $numSpaces);

        if ($side == 'left')
            $string = $indent.$string;
        if ($side == 'rigth')
            $string = $string.$indent;
        if ($side == 'center') {
            $spaces = $numSpaces/2;
            $string = str_repeat(' ', ceil($spaces)).$string.str_repeat(' ', floor($spaces));
        }

        return $string;
    }


    /**
     * @return array Таблица в массиве
     */
    private function getTable() {
        $res=array();
        $res[] = array('Сотрудник', 'Часы', 'Овертайм', 'Ставка', 'Зарплата');

        foreach ($this->getList() as $employee) {
            $res[] = array( $employee->name, $employee->getTotalHoursWorked(), $employee->getOvertimeHours(), $employee->rate, $employee->getSalary() );
        }

        $res[] = array('Всего', $this->getOverallHours(), $this->getOverallOvertime(),'', $this->getOverallSalary());

        return $res;


    }


    /**
     * @return string Отформатированная таблица
     */
    public function getConsoleTable() {
        $res = '';

        $table = $this->getTable();
        $column= array();
        $col = null;

        for ($y=0; $y<count($table[0]); $y++) { // Ширина столбцов
//         foreach ($y=0; $y<count($table[0]); $y++) { // Ширина столбцов
            for ( $i = 0; $i < count($table); $i++ ) {
                if ( (is_null($col)) or (mb_strlen($table[$i][$y]) > $col) ) {
                    $col = mb_strlen($table[$i][$y]);
                }
            }
            $column[]=$col+1;
            $col = null;
        }

        foreach ($table as $string) {
            foreach ($string as $key=>$word) {
                $res.= ($key==0)? $this->indent($word, $column[$key], 'rigth'): $this->indent($word, $column[$key], 'left');
            }
            $res.=PHP_EOL;
        }
        return $res;
    }
}

$employess = new Employees();

$tmp = new Employee('Иванов Иван', 10);
$tmp->setHours(array(40,40,40,40));
$employess->add($tmp);

$tmp = new Employee('Петров Петр', 8);
$tmp->setHours(array(40,40,10,50));
$employess->add($tmp);

$tmp = new Employee('Сидоров Сидр', 9);
$tmp->setHours(array(40,50,10,50));
$employess->add($tmp);

$tmp = new Employee('Васильев Василий', 10);
$tmp->setHours(array(40,40,10,70));
$employess->add($tmp);


echo ($employess->getConsoleTable());

function myAssertArray($a, $b) {
    if (serialize($a) == serialize($b))
        echo 'OK'.PHP_EOL;
    else
        echo 'ERROR: '.$a.' Ожидается: '.$b.PHP_EOL;
}



function testIndent() {
    $string = new Employees();
    myAssertArray($string->indent('строка', 10, 'left'), '    строка');
    myAssertArray($string->indent('строка', 10, 'rigth'), 'строка    ');
    myAssertArray($string->indent('строка', 10, 'center'), '  строка  ');
    myAssertArray($string->indent('строк', 10, 'center'), '   строк  ');
    myAssertArray($string->indent('10', 3, 'center'), ' 10');
}
//testIndent();

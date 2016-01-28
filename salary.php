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
    private function padRigth ($string, $length) { // Создает отступ строки $string справа до длины $length
        if (mb_strlen($string)>=$length)
            die('Ошибка! Увеличте ширину столбца. Минимальная ширина 1-го столбца: '.(mb_strlen($string)+1).PHP_EOL);
        do {
            $string.=' ';
        }
        while (mb_strlen($string) != $length);
        return $string;
    }
    private function padLeft ($string, $length) { // Создает отступ строки $string слева до длины $length
        if (mb_strlen($string)>=$length)
            die('Ошибка! Увеличте ширину столбца. Минимальная ширина 2-го и следующих столбцов: '.(mb_strlen($string)+1).PHP_EOL);
        $insert='';
        do {
            $insert.=' ';
        }
        while (mb_strlen($insert)+mb_strlen($string) != $length);
        $string = $insert.$string;
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
     * @return string
     */
    public function getConsoleTable() {
        $res = '';

        $col1 = 13; //ширина столбцов
        $colNext = 9;

        $table = $this->getTable();

        foreach ($table as $string) {
            foreach ($string as $key=>$word) {
                $res.= ($key==0)? $this->padRigth($word, $col1): $this->padLeft($word, $colNext);
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


echo ($employess->getConsoleTable());



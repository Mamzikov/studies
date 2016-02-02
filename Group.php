<?php

/**
 * Created by Alexander Mamzikov
 * Date: 30.01.2016, Time: 18:16
 * Contact email: al.mamzikov@gmail.com
 */

error_reporting(-1);
mb_internal_encoding('utf-8');


class EmployeeGroup {
	public $position; //должность
	public $rank; // ранг сотрудника
	public $boss = false; // признак руководителя
	public $salary; //оклад в месяц
	public $kofe; // расход кофе в месяц
	public $product; // произведённый продукт в месяц
	public $amount; // количество сотрудников в группе с одинаковыми свойствами

	const INCREASE_SALARY = 25; // увеличение зарплаты в зависимости от ранга в процентах
	const INCREASE_SALARY_BOSS = 50; // увеличение зарплаты руководителя в процентах
	const INCREASE_KOFE_BOSS = 2; // увеличение потребления кофе руководителем
	const DIFF_PRODUCT_BOSS = 0; // количество произведённого продукта руководителем

	private $parent;

	public function __construct($position, $rank, $kofe, $product, $salary, $amount) {
		$this->position=$position;
		$this->rank=$rank;
		$this->kofe=$kofe;
		$this->product=$product;
		$this->salary=$salary;
		$this->amount=$amount;
	}

	public function setBoss($boss) {
		$this->boss = $boss;
		return $this;
	}

	public function setParent($obj) {
		$this->parent = $obj;
	}

	public function getParentName() {
		return $this->parent->getName();
	}

	public function getSalary() {
		$salary = $this->salary+(($this->salary/100)*self::INCREASE_SALARY)*($this->rank-1);
		$salary = ($this->boss==true) ? $salary+($salary/100)*self::INCREASE_SALARY_BOSS: $salary;
		return $salary;
	}
	public function getKofe() {
		$kofe = ($this->boss==true) ? $this->kofe*self::INCREASE_KOFE_BOSS : $this->kofe;
		return $kofe;
	}
	public function getProduct() {
		$product = ($this->boss==true) ? $this->product*self::DIFF_PRODUCT_BOSS : $this->product;
		return $product;
	}
}

class DepartmentVector {

	private $employees=array();
	private $departament; // наименование депортамента


	function __construct($name) {
		$this->departament = $name;
	}

	public function getName() {
		return $this->departament;
	}

	public function addEmployee(EmployeeGroup $employees) {
		$this->employees[] = $employees;
		return $employees;
	}

	public function getEmployeesCount() {
		$res=0;
		foreach ($this->employees as $employee) {
			$res+=$employee->amount;
		}
		return $res;
	}

	public function getOverallSalary() {
		$res=0;
		foreach ($this->employees as $employee) {
			$res+=$employee->amount*$employee->getSalary();
		}
		return $res;
	}

	public function getOverallKofe() {
		$res=0;
		foreach ($this->employees as $employee) {
			$res+=$employee->amount*$employee->getKofe();
		}
		return $res;

	}

	public function getOverallProduct() {
		$res=0;
		foreach ($this->employees as $employee) {
			$res+=$employee->amount*$employee->getProduct();
		}
		return $res;
	}
	public function getEfficiency() {
		return round($this->getOverallSalary()/$this->getOverallProduct(), 1);
	}
}

class VectorCompany {
	private $departments;

	public function addDepartment(DepartmentVector $department) {
		$this->departments[] = $department;
		return $department;
	}

	public function getTable() {
		$res = array();

				$res[] = array('Депортамент',
				       'Расходы на зарплату',
		               'Кол-во сотрудников',
		               'Выпито кофе',
		               'Продукт, стр.',
		               'Эффективность','Test');

		$overall_cnt = 0;
		$overall_sal = 0;
		$overall_kofe = 0;
		$overall_product = 0;
		$overall_effic = 0;

		foreach ($this->getData() as $row) {

//			$res[] = $row;
			$res[] = array($row['name'],$row['salary'],$row['emp_count'],$row['kofe'],$row['product'],$row['effic'],$row['test']);


			$overall_cnt+=$row['emp_count'];
			$overall_sal+=$row['salary'];
			$overall_kofe+=$row['kofe'];
			$overall_product+=$row['product'];
			$overall_effic+=$row['effic'];

		}
		$amountDepartment = count($this->departments);
		$average_cnt = round($overall_cnt/$amountDepartment, 1);
		$average_sal = round($overall_sal/$amountDepartment, 1);
		$average_kofe = round($overall_kofe/$amountDepartment, 1);
		$average_prod = round($overall_product/$amountDepartment, 1);
		$average_effic = round($overall_effic/$amountDepartment, 1);


				$res[] = array('Среднее',
				       $average_sal,
		               $average_cnt,
		               $average_kofe,
		               $average_prod,
		               $average_effic,'n/a');
				$res[] = array('Всего',
				       $overall_sal,
		               $overall_cnt,
		               $overall_kofe,
		               $overall_product,
		               $overall_effic,'n/a');

		return $res;

	}

	public function getData() {
		$res = array();

		foreach ($this->departments as $department) {
			$res[] = array('name'=>$department->getName(),
			               'test'=>$department->getEmployeesCount()*2,
			               'emp_count'=>$department->getEmployeesCount(),
			               'salary'=>$department->getOverallSalary(),
			               'kofe'=>$department->getOverallKofe(),
			               'product'=>$department->getOverallProduct(),
			               'effic'=>$department->getEfficiency());

		}

		return $res;
	}

}

class Report {
	const LEFT='left';
	const RIGHT='right';
	const CENTER= 'center';

	/**
	 * @param string $string Исходная строка
	 * @param int $length Необходимая длина строки
	 * @param string $side С какой стороны необходимы пробелы
	 * @return string Строка дополненная пробелами до необходимой длины
	 */
	private function indent ($string, $length, $side) {
		if (mb_strlen($string)>=$length)
			die('Ошибка! Ширина столбца меньше кол-ва символов строки. Минимальная ширина столбца: '.(mb_strlen($string)+1).PHP_EOL);
		$numSpaces = $length-mb_strlen($string);
		$indent = str_repeat(' ', $numSpaces);
		switch ($side) {
			case 'left':
				$string = $indent.$string;
				break;
			case 'right':
				$string = $string.$indent;
				break;
			case 'center':
				$spaces = $numSpaces/2;
				$string = str_repeat(' ', ceil($spaces)).$string.str_repeat(' ', floor($spaces));
				break;
		}

		return $string;
	}

	/**
	 * @param array $table
	 * @return string Отформатированная таблица
	 */
	public function getConsoleTable($table) {
		$res = '';

//		$column= array();
//		$col = null;
//
//		for ($y=0; $y<count($table[0]); $y++) { // Ширина столбцов (работает только с цифровыми ключами)
//			for ( $i = 0; $i < count($table); $i++ ) {
//				if ( (is_null($col)) or (mb_strlen($table[$i][$y]) > $col) ) {
//					$col = mb_strlen($table[$i][$y]);
//				}
//			}
//			$column[]=$col+1;
//			$col = null;
//		}


		$column = array();
		foreach ($table as $rowKey=>$row) { // Ширина столбцов (работает со всеми ключами)
			foreach ($row as $key=>$item) {  //
				if ((!isset($column[$key])) or (mb_strlen($item) > $column[$key]) ) {
					$column[$key] = mb_strlen($item);
				}
			}
		}

		foreach ($table as $string) {
			foreach ($string as $key=>$word) {
				$res.= ($key=='name')? $this->indent($word, $column[$key]+1, self::RIGHT): $this->indent($word, $column[$key]+1, self::CENTER);
			}
			$res.=PHP_EOL;
		}
		return $res;
	}
}



$department = new DepartmentVector('Закупки');
$department->addEmployee(new EmployeeGroup('Менеджер', 1, 20, 200, 500, 9));
$department->addEmployee(new EmployeeGroup('Менеджер', 2, 20, 200, 500, 3));
$department->addEmployee(new EmployeeGroup('Менеджер', 3, 20, 200, 500, 2));
$department->addEmployee(new EmployeeGroup('Маркетолог', 1, 15, 150, 400, 2));
$department->addEmployee(new EmployeeGroup('Менеджер', 2, 20, 200, 500, 1))->setBoss(true);

$company = new VectorCompany();
$company->addDepartment($department);

$department = new DepartmentVector('Продажи');
$department->addEmployee(new EmployeeGroup('Менеджер', 1, 20, 200, 500, 12));
$department->addEmployee(new EmployeeGroup('Аналитик', 1, 50, 5, 800, 3));
$department->addEmployee(new EmployeeGroup('Аналитик', 2, 50, 5, 800, 2));
$department->addEmployee(new EmployeeGroup('Маркетолог', 1, 15, 150, 400, 6));
$department->addEmployee(new EmployeeGroup('Маркетолог', 2, 15, 150, 400, 1))->setBoss(true);

$company->addDepartment($department);

$department = new DepartmentVector('Реклама');
$department->addEmployee(new EmployeeGroup('Маркетолог', 1, 15, 150, 400, 15));
$department->addEmployee(new EmployeeGroup('Маркетолог', 2, 15, 150, 400, 10));
$department->addEmployee(new EmployeeGroup('Менеджер', 1, 20, 200, 500, 8));
$department->addEmployee(new EmployeeGroup('Инженер', 1, 5, 50, 200, 2));
$department->addEmployee(new EmployeeGroup('Маркетолог', 3, 15, 150, 400, 1))->setBoss(true);

$company->addDepartment($department);

$department = new DepartmentVector('Логистика');
$department->addEmployee(new EmployeeGroup('Менеджер', 1, 20, 200, 500, 13));
$department->addEmployee(new EmployeeGroup('Менеджер', 2, 20, 200, 500, 5));
$department->addEmployee(new EmployeeGroup('Инженер', 1, 5, 50, 200, 5));
$department->addEmployee(new EmployeeGroup('Менеджер', 1, 20, 200, 500, 1))->setBoss(true);

$company->addDepartment($department);

//print_r($company->getTable());

$table = new Report();
echo $table->getConsoleTable($company->getTable());;





function myAssert($a, $b, $message=null) {
	if ($a == $b)
		echo 'OK'.PHP_EOL;
	else
		echo 'ERROR: результат выполнения: '.$a.', Ожидается: '.$b.'. Function Error: '.$message.PHP_EOL;
}


function testDepartmentVectorMethods() {

	$department = new DepartmentVector('Закупки');
	$department->addEmployee(new EmployeeGroup('Менеджер', 3, 20, 200, 500, 2));
	$department->addEmployee(new EmployeeGroup('Менеджер', 2, 20, 200, 500, 2));
	$department->addEmployee(new EmployeeGroup('Маркетолог', 1, 15, 150, 400, 2));
	$department->addEmployee(new EmployeeGroup('Менеджер', 2, 20, 200, 500, 1))->boss=true;

	myAssert($department->getEmployeesCount(), 7, 'getEmployeesCount');
	myAssert($department->getOverallSalary(), 4487.5, 'getOverallSalary');
	myAssert($department->getOverallKofe(), 150, 'getOverallKofe');
	myAssert($department->getOverallProduct(), 1100, 'getOverallProduct');
	myAssert($department->getEfficiency(), 4.1, 'getEfficiency');
}
//testDepartmentVectorMethods();




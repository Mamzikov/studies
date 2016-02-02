<?php
/**
 * Created by Alexander Mamzikov
 * Date: 02.02.2016, Time: 19:46
 * Contact email: al.mamzikov@gmail.com
 */

include 'Group.php';

class Goods {
	public $nameGood; // наименование товара
	public $costPrice; // себестоимость товара
	public $stock=false; // Признак распродажи
	public $amt;

	const DISCOUNT = 20; // Размер скидки
	const MARGIN = 30; // Размер наценки

	public function __construct($nameGood, $costPrice, $amt) {
		$this->nameGood = $nameGood;
		$this->costPrice = $costPrice;
		$this->amt = $amt;
	}
	public function getPriceGood () {
		$price = ($this->stock == true) ? $this->costPrice+($this->costPrice/100*(self::MARGIN-self::DISCOUNT)) : $this->costPrice+($this->costPrice/100*self::MARGIN) ;
		return round($price, 2);
	}

}

class DepartmentStore {
	public $name; // имя отдела
	private $goods = array();

	public function __construct($name) {
		$this->name=$name;
	}

	public function getNameDepart() {
		return $this->name;
	}

	public function addGoods (Goods $goods) {
		$this->goods[]=$goods;
		return $goods;
	}

	public function getGoodsAmt () {
		$res=0;
		foreach ($this->goods as $good) {
			$res+=$good->amt;
		}
		return $res;
	}
	public function getGoodsStockAmt () {
		$res=0;
		foreach ($this->goods as $good) {
			if ($good->stock)
				$res+=$good->amt;
		}
		return $res;
	}
	public function getGoodsStockPriceSum () {
		$res=0;
		foreach ($this->goods as $good) {
			if ($good->stock)
				$res+=$good->getPriceGood()*$good->amt;
		}
		return $res;
	}

	public function getGoodsPriceSum () {
		$res=0;
		foreach ($this->goods as $good) {
			$res+=$good->getPriceGood()*$good->amt;
		}
		return $res;
	}
	public function getGoodsCostPriceSum () {
		$res=0;
		foreach ($this->goods as $good) {
			$res+=$good->costPrice*$good->amt;
		}
		return $res;
	}
	public function getDepartmentProfit () {
		$res=0;
		foreach ($this->goods as $good) {
			$res+=$good->getPriceGood()-$good->costPrice;
		}
		return $res;
	}


}

class Store {
	private $departments=array();

	public function addDepartments (DepartmentStore $department) {
		$this->departments[]=$department;
		return $department;
	}
	public function getDataStore () {
		$res=array();
		foreach ($this->departments as $row) {
			$res[] = array ('name'=>$row->getNameDepart(),
		                    'count'=>$row->getGoodsAmt(),
		                    'price'=>$row->getGoodsPriceSum(),
		                    'stock_cnt'=>$row->getGoodsStockAmt(),
		                    'stock_prc'=>$row->getGoodsStockPriceSum(),
							'profit'=>$row->getDepartmentProfit()
							);
		}
		return $res;
	}
	public function getTableStore() {
		$res=array();
		$res[] = array('Отдел',
		               'Кол-во товара',
		               'На сумму (по рознице)',
		               'Кол-во акционных товаров',
		               'На сумму (по рознице)',
		               'Маржа');

		$overall_cnt=0;
		$overall_prc=0;
		$overall_stock_cnt=0;
		$overall_stock_prc=0;
		$overall_profit=0;

		foreach ($this->getDataStore() as $row) {
			$res[] = array($row['name'], $row['count'], $row['price'], $row['stock_cnt'], $row['stock_prc'], $row['profit']);
			$overall_cnt+=$row['count'];
			$overall_prc+=$row['price'];
			$overall_stock_cnt+=$row['stock_cnt'];
			$overall_stock_prc+=$row['stock_prc'];
			$overall_profit+=$row['profit'];
		}

		$res[] = array('Итого',
		               $overall_cnt,
		               $overall_prc,
		               $overall_stock_cnt,
		               $overall_stock_prc,
		               $overall_profit);
		return $res;

	}

}

$depart = new DepartmentStore('Овощи-Фрукты');
$depart->addGoods(new Goods('Морковь', 20, 12));
$depart->addGoods(new Goods('Лук', 5, 20));
$depart->addGoods(new Goods('Помидор', 95, 15));
$depart->addGoods(new Goods('Огурцы', 110, 8));
$depart->addGoods(new Goods('Картофель', 18, 180))->stock=true;

$store = new Store();
$store->addDepartments($depart);

$depart = new DepartmentStore('Одежда');
$depart->addGoods(new Goods('Рубашка', 500, 12));
$depart->addGoods(new Goods('Брюки', 400, 6));
$depart->addGoods(new Goods('Шляпа', 80, 600))->stock=true;
$depart->addGoods(new Goods('Майка', 300, 25));

$store->addDepartments($depart);

$depart = new DepartmentStore('Напитки');
$depart->addGoods(new Goods('Лимонад', 10, 200));
$depart->addGoods(new Goods('Пиво', 30, 100));
$depart->addGoods(new Goods('Водка', 180, 600))->stock=true;
$depart->addGoods(new Goods('Сок', 60, 25));

$store->addDepartments($depart);

//print_r($store->getTableStore());
$table = new Report();
echo $table->getConsoleTable($store->getTableStore());


//function myAssert($a, $b, $message=null) {
//	if ($a == $b)
//		echo 'OK'.PHP_EOL;
//	else
//		echo 'ERROR: результат выполнения: '.$a.', Ожидается: '.$b.'. Method Error: '.$message.PHP_EOL;



//function testMethods() {
//
//	$good = new Goods('Марковь', 20, 3);
//	myAssert($good->getPriceGood(), 26, 'getPriceGood()');
//	$good = new Goods('Помидор', 30, 5);
//	$good->stock=true;
//	myAssert($good->getPriceGood(), 33, 'getPriceGood()');
//
//	$depart = new DepartmentStore('Овощи');
//	$depart->addGoods(new Goods('Марковь', 20, 3));
//	$depart->addGoods(new Goods('Памидор', 30, 5))->stock=true;
//	$depart->addGoods(new Goods('Картофель', 30.60, 8));
//	myAssert($depart->getGoodsAmt(), 16, 'getGoodsAmt');
//	myAssert($depart->getGoodsPriceSum(), 561.24, 'getGoodsSum');
//	myAssert($depart->getGoodsCostPriceSum(), 454.8, 'getGoodsCostPriceSum');
//	myAssert($depart->getDepartmentProfit(), 18.18, 'getDepartmentProfit');
//	myAssert($depart->getGoodsStockAmt(), 5, 'getGoodsStockAmt');
//	myAssert($depart->getGoodsStockPriceSum(), 165, 'getGoodsStockPriceSum');
//}

//testMethods();
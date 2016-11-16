<?php
// 2016-11-13
namespace Dfe\Omise\T\Api;
use Dfe\Omise\Api\Customer as AC;
use Dfe\Omise\Api\O as AO;
use OmiseCustomer as C;
class Customer extends TestCase {
	/**
	 * @test
	 * 2016-11-14
	 */
	public function t00() {}

	/**
	 * 2016-11-13
	 */
	public function t01() {
		/** @var C $c */
		$c = C::create([
			'email' => 'admin@mage2.pro',
			'description' => 'Mage2.PRO Customer'
		]);
		$this->assertFalse(AC::_isDestroyed($c));
		/** @var C $c2 */
		$c2 = C::retrieve(AO::_id($c));
		$this->assertFalse(AC::_isDestroyed($c2));
		$c->destroy();
		$this->assertTrue(AC::_isDestroyed($c));
	}

	/**
	 * 2016-11-14
	 * Omise допускает создание множества покупателей с одинаковым email.
	 */
	public function t02() {
		/** @var array(string => string) $d */
		$d = ['email' => 'admin@mage2.pro'];
		/** @var int $i */
		$i = 5;
		/** @var C[] $c */
		$c = [];
		while ($i--) {
			$c[]= C::create($d);
		}
		array_map(function(C $c) {$c->destroy();}, $c);
	}

	/**
	 * 2016-11-14
	 * Корявое удаление покупателей.
	 * Недостатки:
	 * 1) retrieve получает не всех покупателей, а только первые 20:
	 * https://www.omise.co/customers-api#customers-list
	 * 2) для удаления покупателя через этот официальный корявый PHP API
	 * мы вынуждены заново делать retrieve для конкретного покупателя.
	 */
	public function t03() {
		array_map(function($id) {
			C::retrieve($id)->destroy()
		;}, array_column(C::retrieve()['data'], 'id'));
	}

	/**
	 * @test
	 * 2016-11-17
	 * Deletes test customers by email.
	 * Недостатки:
	 * 1) retrieve получает не всех покупателей, а только первые 20:
	 * https://www.omise.co/customers-api#customers-list
	 * 2) для удаления покупателя через этот официальный корявый PHP API
	 * мы вынуждены заново делать retrieve для конкретного покупателя.
	 */
	public function t04() {
		array_map(function(array $c) {
			if ('admin@mage2.pro' === $c['email']) {
				C::retrieve($c['id'])->destroy();
			}
		;}, C::retrieve()['data']);
	}

	/**
	 * 2016-11-13
	 */
	public function tRetrieve() {
		/** @var C $customer */
		$c = C::retrieve('cust_test_55zq4ihfaz2csc4c1s4');
		$this->assertTrue(true);
	}

	/**
	 * 2016-11-14
	 */
	public function tRetrieveNonExistent() {
		$this->expectException(\OmiseException::class);
		C::retrieve(df_uid());
	}

	/**
	 * 2016-11-13
	 */
	public function tDelete() {
		/** @var C $customer */
		$c = C::retrieve('cust_test_55zq4ihfaz2csc4c1s4');
		$c->destroy();
		$this->assertTrue($c->isDestroyed());
	}
}
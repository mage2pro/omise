<?php
# 2016-11-13
namespace Dfe\Omise\Test;
use OmiseCustomer as C;
final class Customer extends TestCase {
	/** 2016-11-14 @test */
	function t00():void {}

	/** 2016-11-14 Omise допускает создание множества покупателей с одинаковым email. */
	function t02():void {
		$d = ['email' => 'admin@mage2.pro']; /** @var array(string => string) $d */
		$i = 5; /** @var int $i */
		$c = []; /** @var C[] $c */
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
	function t03():void {
		array_map(function($id) {
			C::retrieve($id)->destroy()
		;}, array_column(C::retrieve()['data'], 'id'));
	}

	/**
	 * 2016-11-17
	 * Deletes test customers by email.
	 * Недостатки:
	 * 1) retrieve получает не всех покупателей, а только первые 20:
	 * https://www.omise.co/customers-api#customers-list
	 * 2) для удаления покупателя через этот официальный корявый PHP API
	 * мы вынуждены заново делать retrieve для конкретного покупателя.
	 */
	function t04():void {
		array_map(function(array $c):void {
			if ('admin@mage2.pro' === $c['email']) {
				C::retrieve($c['id'])->destroy();
			}
		;}, C::retrieve()['data']);
	}

	/** 2016-11-13 */
	function tRetrieve() {
		/** @var C $customer */
		$c = C::retrieve('cust_test_55zq4ihfaz2csc4c1s4');
		$this->assertTrue(true);
	}

	/** 2016-11-14 */
	function tRetrieveNonExistent() {
		$this->expectException(\OmiseException::class);
		C::retrieve(df_uid());
	}
}
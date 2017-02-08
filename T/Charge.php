<?php
// 2016-11-15
// https://www.omise.co/tokens-api
namespace Dfe\Omise\T;
use Dfe\Omise\Api\Customer as AC;
use Dfe\Omise\Api\O as AO;
use OmiseCharge as Ch;
use OmiseCustomer as C;
use OmiseToken as T;
final class Charge extends TestCase {
	/**
	 * @test
	 * 2016-11-15
	 */
	public function t00() {}

	/**
	 * 2016-11-15
	 */
	public function t01() {
		/** @var T $t */
		$t = T::create(['card' => [
			'expiration_month' => 7
			,'expiration_year' => 2019
			,'name' => 'DMITRY FEDYUK'
			,'number' => '5555555555554444'
			,'security_code' => '123'
		]]);
		/** @var C $c */
		$c = C::create([
			'card' => $t['id']
			,'description' => 'DMITRY FEDYUK'
			,'email' => 'admin@mage2.pro'
		]);
		/** @var Ch $charge */
		$ch = Ch::create([
			// 2016-11-15
			// 20 бат — наименьший размер платежа
			// https://www.omise.co/currency-and-amount#amount
			'amount' => 2000
			,'card' => AC::_cardIdLast($c)
			,'currency' => 'THB'
			,'customer' => $c['id']
		]);
		/** @var array(string => string|bool|int|null) $values */
		$values = AO::_values($ch);
		$this->assertTrue(is_array($values));
		/**
		 * 2016-11-16
			{
				"object": "card",
				"id": "card_test_560jgvu90914d44h1vx",
				"livemode": false,
				"location": "/customers/cust_test_560jgw6s43s7i4ydd8r/cards/card_test_560jgvu90914d44h1vx",
				"country": "us",
				"city": null,
				"postal_code": null,
				"financing": "",
				"bank": "",
				"last_digits": "4444",
				"brand": "MasterCard",
				"expiration_month": 7,
				"expiration_year": 2019,
				"fingerprint": "/uCzRPQQRUDr8JvGUjKf7Xn10VRJeQ7oBZ1Zt7gLvWs=",
				"name": "DMITRY FEDYUK",
				"security_code_check": true,
				"created": "2016-11-15T22:00:49Z"
			}
		 * @var array(string => string|bool|int|null) $card
		 */
		$card = $ch['card'];
		// 2016-11-16
		// «card_test_560jfnmlfev5n2rkl9i»
		$this->assertStringStartsWith('card_', $card['id']);
		xdebug_break();
	}
}
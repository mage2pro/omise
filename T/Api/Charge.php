<?php
// 2016-11-15
// https://www.omise.co/tokens-api
namespace Dfe\Omise\T\Api;
use Dfe\Omise\Api\Customer as AC;
use Dfe\Omise\Api\O as AO;
use OmiseCharge as Ch;
use OmiseCustomer as C;
use OmiseToken as T;
class Charge extends TestCase {
	/**
	 * 2016-11-15
	 */
	public function t00() {}

	/**
	 * @test
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
		/** @var Ch $charge */
		$ch = Ch::create([
			// 2016-11-15
			// 20 бат — наименьший размер платежа
			// https://www.omise.co/currency-and-amount#amount
			'amount' => 2000,
			'currency' => 'THB',
			'card' => AO::_id($t)
		]);
	}
}
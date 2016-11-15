<?php
// 2016-11-14
// https://www.omise.co/tokens-api
namespace Dfe\Omise\T\Api;
use Dfe\Omise\Api\Customer as AC;
use Dfe\Omise\Api\O as AO;
use OmiseCustomer as C;
use OmiseToken as T;
class Token extends TestCase {
	/**
	 * @test
	 * 2016-11-14
	 */
	public function t00() {}

	/**
	 * 2016-11-14
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
		$this->assertStringStartsWith('tokn_', AO::_id($t));
		$this->assertFalse($t['used']);
	}

	/**
	 * Создаём покупателя и добавляем к нему несколько карт.
	 * 2016-11-14
	 */
	public function t02() {
		/** @var C $c */
		$c = C::create([
			'email' => 'admin@mage2.pro',
			'description' => 'Mage2.PRO Customer'
		]);
		/** @var int $i */
		$i = 3;
		while ($i--) {
			/** @var T $t */
			$t = T::create(['card' => [
				'expiration_month' => 7
				,'expiration_year' => 2019
				,'name' => 'DMITRY FEDYUK ' . $i
				,'number' => '5555555555554444'
				,'security_code' => '123'
			]]);
			$r = $c->update(['card' => AO::_id($t)]);
			xdebug_break();
		}
		/** @var C $c2 */
		$c2 = C::retrieve(AO::_id($c));
		print_r(AC::_cards($c2));
		$c2->destroy();
	}
}
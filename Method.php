<?php
// 2016-11-10
namespace Dfe\Omise;
/** @method Settings s() */
class Method extends \Df\Payment\Method {
	/**
	 * 2016-11-10
	 * @override
	 * @see \Df\Payment\Method::charge()
	 * @param float $amount
	 * @param bool|null $capture [optional]
	 * @return void
	 */
	protected function charge($amount, $capture = true) {
	}

	/**
	 * 2016-11-10
	 * @override
	 * @see \Df\Payment\Method::iiaKeys()
	 * @used-by \Df\Payment\Method::assignData()
	 * @return string[]
	 */
	protected function iiaKeys() {return [self::$TOKEN];}

	/**
	 * 2016-11-10
	 * @var string
	 */
	private static $TOKEN = 'token';
}
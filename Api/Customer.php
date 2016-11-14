<?php
// 2016-11-13
namespace Dfe\Omise\Api;
use OmiseCustomer as Sb;
class Customer extends Sb {
	/**
	 * 2016-11-14
	 * https://github.com/omise/omise-php/issues/43
	 * @param Sb $o
	 * @return bool
	 */
	public static function _isDestroyed(Sb $o) {return !!$o->isDestroyed();}
}
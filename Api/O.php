<?php
// 2016-11-14
namespace Dfe\Omise\Api;
use OmiseApiResource as Sb;
final class O extends Sb {
	/**
	 * 2016-11-16
	 * «Provide the public access to the @see \OmiseObject::$_values array at the whole
	 * with a method like getValues()»: https://github.com/omise/omise-php/issues/53
	 * @used-by \Dfe\Omise\Exception\Charge::message()
	 * @used-by \Dfe\Omise\Exception\Charge::sentryContext()
	 * @used-by \Dfe\Omise\Facade\O::toArray()
	 * @param Sb $o
	 * @return array(string => string|bool|int|null)
	 */
	public static function _values(Sb $o) {return $o->_values;}
}
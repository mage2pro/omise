<?php
// 2016-11-13
namespace Dfe\Omise;
use OmiseObject as O;
class ApiObject extends O {
	/**
	 * 2016-11-13
	 * @param O $o
	 * @return array(string => mixed)
	 */
	public static function values(O $o) {return $o->_values;}
}
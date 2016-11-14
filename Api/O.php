<?php
// 2016-11-14
namespace Dfe\Omise\Api;
use OmiseApiResource as Sb;
class O extends Sb {
 	/**
	 * 2016-11-14
	 * @param Sb $o
	 * @return string
	 */
	public static function _id(Sb $o) {return $o['id'];}
}
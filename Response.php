<?php
namespace Dfe\Omise;
class Response {
	/**
	 * 2016-11-14
	 * @param array(string => mixed) $data
	 * @return string
	 */
	public static function cardS(array $data) {return
		sprintf('···· %s (%s)', dfa($data, 'last_digits'), dfa($data, 'brand'))
	;}
}
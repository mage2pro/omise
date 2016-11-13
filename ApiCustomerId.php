<?php
// 2016-11-13
namespace Dfe\Omise;
use Magento\Customer\Model\Customer as C;
class ApiCustomerId {
	/**
	 * 2016-11-13
	 * @param C|null $c [optional]
	 * @return string|null
	 */
	public static function get($c = null) {return df_customer_info_get($c, self::$KEY);}

	/**
	 * 2016-11-13
	 * Если $id равно null, то ключ удалится: @see dfo()
	 * @param string|null $id
	 */
	public static function save($id) {df_customer_info_save(self::$KEY, $id);}

	/**
	 * 2016-11-13
	 * @var string
	 */
	private static $KEY = 'omise';
}
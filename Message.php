<?php
// 2016-11-16
namespace Dfe\Omise;
use Magento\Sales\Model\Order\Payment\Transaction as T;
class Message extends \Df\Core\A {
	/**
	 * 2016-08-20
	 * @return string
	 */
	public function card() {return $this->cardS($this->_card());}

	/**
	 * 2016-11-14
	 * @param array(string => mixed) $data
	 * @return string
	 */
	public static function cardS(array $data) {return
		sprintf('···· %s (%s)', dfa($data, 'last_digits'), dfa($data, 'brand'))
	;}

	/**
	 * 2016-11-16
	 * @return string
	 */
	public function country() {return df_country_ctn(strtoupper($this->_card('country')));}

	/**
	 * 2016-11-16
	 * @return string
	 */
	public function expires() {return implode(' / ', [
		sprintf('%02d', $this->_card('expiration_month')), $this->_card('expiration_year')
	]);}

	/**
	 * 2016-11-16
	 * @return string
	 */
	public function id() {return $this['id'];}

	/**
	 * 2016-11-16
	 * @param T $t
	 * @return self
	 */
	public static function i(T $t) {return new static(df_json_decode(df_trans_raw_details(
		$t, Method::IIA_TR_RESPONSE
	)));}

	/**
	 * 2016-11-16
	 * @param string|null $key [optional]
	 * @return mixed|array(string => mixed)|null
	 */
	private function _card($key = null) {return $this->a(df_cc_path('card', $key));}
}



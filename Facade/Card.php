<?php
namespace Dfe\Omise\Facade;
// 2017-02-11 https://www.omise.co/cards-api
final class Card extends \Df\StripeClone\Facade\Card {
	/**
	 * 2017-02-11
	 * @used-by \Df\StripeClone\Facade\Card::create()
	 * @param array(string => string) $p
	 */
	function __construct(array $p) {$this->_p = $p;}

	/**
	 * 2017-02-11
	 * 2017-10-07 «Card brand (e.g.: Visa, Mastercard,...)»
	 * Type: string.
	 * @override
	 * @see \Df\StripeClone\Facade\Card::brand()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @used-by \Df\StripeClone\CardFormatter::label()
	 * @return string
	 */
	function brand() {return $this->_p['brand'];}

	/**
	 * 2017-02-11
	 * 2017-10-07 An ISO-2 code.
	 * «Country code based on the card number following the ISO 3166 standard.
	 * Note that this information is given for information only
	 * and may not always be 100% accurate.»
	 * Type: string.
	 * https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements
	 * @see \Df\StripeClone\Facade\Card::country()
	 * @used-by \Df\StripeClone\CardFormatter::country()
	 * @return string
	 */
	function country() {return $this->_p['country'];}

	/**
	 * 2017-02-11
	 * 2017-10-07 «Card expiration month (1-12)»
	 * Type: integer.
	 * @see \Df\StripeClone\Facade\Card::expMonth()
	 * @used-by \Df\StripeClone\CardFormatter::exp()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @return int
	 */
	function expMonth() {return intval($this->_p['expiration_month']);}

	/**
	 * 2017-02-11
	 * 2017-10-07 «Card expiration year»
	 * Type: integer.
	 * @see \Df\StripeClone\Facade\Card::expYear()
	 * @used-by \Df\StripeClone\CardFormatter::exp()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @return int
	 */
	function expYear() {return intval($this->_p['expiration_year']);}

	/**
	 * 2017-02-11
	 * 2017-10-07 «The `CARD_ID`»
	 * Type: object_id.
	 * @see \Df\StripeClone\Facade\Card::id()
	 * @used-by \Df\StripeClone\ConfigProvider::cards()
	 * @used-by \Df\StripeClone\Facade\Customer::cardIdForJustCreated()
	 * @return string
	 */
	function id() {return $this->_p['id'];}

	/**
	 * 2017-02-11
	 * 2017-10-07 «Last 4 digits of the card number»
	 * Type: string.
	 * @see \Df\StripeClone\Facade\Card::last4()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @used-by \Df\StripeClone\CardFormatter::label()
	 * @return string
	 */
	function last4() {return $this->_p['last_digits'];}

	/**
	 * 2017-02-11
	 * 2017-10-07 «Card owner full name»
	 * Type: string.
	 * @override
	 * @see \Df\StripeClone\Facade\Card::owner()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @return string
	 */
	function owner() {return 'name';}

	/**
	 * 2017-02-11
	 * @var array(string => string)
	 */
	private $_p;
}
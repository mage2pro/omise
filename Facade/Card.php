<?php
namespace Dfe\Omise\Facade;
// 2017-02-11
// https://www.omise.co/cards-api
final class Card implements \Df\StripeClone\Facade\ICard {
	/**
	 * 2017-02-11
	 * @used-by \Df\StripeClone\Facade\Card::create()
	 * @param array(string => string) $p
	 */
	function __construct(array $p) {$this->_p = $p;}

	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Facade\ICard::brand()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @used-by \Df\StripeClone\CardFormatter::label()
	 * @return string
	 */
	function brand() {return $this->_p['brand'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::country()
	 * @used-by \Df\StripeClone\CardFormatter::country()
	 * @return string
	 */
	function country() {return $this->_p['country'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::expMonth()
	 * @used-by \Df\StripeClone\CardFormatter::exp()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @return string|int
	 */
	function expMonth() {return $this->_p['expiration_month'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::expYear()
	 * @used-by \Df\StripeClone\CardFormatter::exp()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @return string
	 */
	function expYear() {return $this->_p['expiration_year'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::id()
	 * @used-by \Df\StripeClone\ConfigProvider::cards()
	 * @used-by \Df\StripeClone\Facade\Customer::cardIdForJustCreated()
	 * @return string
	 */
	function id() {return $this->_p['id'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::last4()
	 * @used-by \Df\StripeClone\CardFormatter::ii()
	 * @used-by \Df\StripeClone\CardFormatter::label()
	 * @return string
	 */
	function last4() {return $this->_p['last_digits'];}

	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Facade\ICard::owner()
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
<?php
namespace Dfe\Omise\Facade;
// 2017-02-11
final class Card implements \Df\StripeClone\Facade\ICard {
	/**
	 * 2017-02-11
	 * @param array(string => string) $p
	 */
	public function __construct(array $p) {$this->_p = $p;}

	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Facade\ICard::brand()
	 * @used-by \Df\StripeClone\CardFormatter::label()
	 * @return string
	 */
	public function brand() {return $this->_p['brand'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::country()
	 * @used-by \Df\StripeClone\CardFormatter::country()
	 * @return string
	 */
	public function country() {return $this->_p['country'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::expMonth()
	 * @used-by \Df\StripeClone\CardFormatter::exp()
	 * @return string
	 */
	public function expMonth() {return $this->_p['expiration_month'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::expYear()
	 * @used-by \Df\StripeClone\CardFormatter::exp()
	 * @return string
	 */
	public function expYear() {return $this->_p['expiration_year'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::id()
	 * @used-by \Df\StripeClone\ConfigProvider::cards()
	 * @used-by \Df\StripeClone\Facade\Customer::cardIdForJustCreated()
	 * @return string
	 */
	public function id() {return $this->_p['id'];}

	/**
	 * 2017-02-11
	 * @see \Df\StripeClone\Facade\ICard::last4()
	 * @used-by \Df\StripeClone\CardFormatter::label()
	 * @return string
	 */
	public function last4() {return $this->_p['last_digits'];}

	/**
	 * 2017-02-11
	 * @var array(string => string)
	 */
	private $_p;
}
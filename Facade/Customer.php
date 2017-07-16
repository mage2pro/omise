<?php
namespace Dfe\Omise\Facade;
use OmiseCustomer as C;
// 2017-02-10
final class Customer extends \Df\StripeClone\Facade\Customer {
	/**
	 * 2017-02-10
	 * https://github.com/omise/omise-php/issues/43
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::_get()
	 * @used-by \Df\StripeClone\Facade\Customer::get()
	 * @param int $id
	 * @return C
	 */
	function _get($id) {
		// 2017-02-18
		// К сожалению, нельз просто написать:
		// return ($c = C::retrieve($id))->isDestroyed() ? null : $c;}
		// На PHP 5.6 будет сбой: [E_PARSE] syntax error, unexpected '->' (T_OBJECT_OPERATOR)
		/** @var C $c */
		$c = C::retrieve($id);
		return $c->isDestroyed() ? null : $c;
	}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::cardAdd()
	 * @used-by \Df\StripeClone\Payer::newCard()
	 * @param C $c
	 * @param string $token
	 * @return string
	 */
	function cardAdd($c, $token) {
		$c->update(['card' => $token]);
		return df_last($this->cardsData($c))['id'];
	}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::create()
	 * @used-by \Df\StripeClone\Payer::newCard()
	 * @param array(string => mixed) $p
	 * @return C
	 */
	function create(array $p) {return C::create($p);}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::id()
	 * @used-by \Df\StripeClone\Payer::newCard()
	 * @param C $c
	 * @return string
	 */
	function id($c) {return $c['id'];}

	/**
	 * 2017-02-11
	 * Ключ $o['cards']['data'] присутствует в объекте даже при отсутствии карт.
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::cardsData()
	 * @used-by cardAdd()
	 * @used-by \Df\StripeClone\Facade\Customer::cards()
	 * @param C $c
	 * @return array(array(string => string))
	 * @see \Dfe\Omise\Facade\Charge::cardData()
	 */
	protected function cardsData($c) {return $c['cards']['data'];}
}
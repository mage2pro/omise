<?php
namespace Dfe\Omise\Facade;
use OmiseCustomer as C;
# 2017-02-10
final class Customer extends \Df\StripeClone\Facade\Customer {
	/**
	 * 2017-02-10
	 * 2022-12-19 We can not declare the $c argument type because it is undeclared in the overriden method.
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::cardAdd()
	 * @used-by \Df\StripeClone\Payer::newCard()
	 * @param C $c
	 */
	function cardAdd($c, string $token):string {
		$c->update(['card' => $token]);
		return df_last($this->cardsData($c))['id'];
	}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::create()
	 * @used-by \Df\StripeClone\Payer::newCard()
	 * @param array(string => mixed) $p
	 */
	function create(array $p):C {return C::create($p);}

	/**
	 * 2017-02-10
	 * 2023-01-06 We can not declare the argument's type because it is undeclared in the overriden method.
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::id()
	 * @used-by \Df\StripeClone\Payer::newCard()
	 * @param C $c
	 */
	function id($c):string {return $c['id'];}

	/**
	 * 2017-02-10 https://github.com/omise/omise-php/issues/43
	 * 2023-01-06 We can not declare the argument's type because it is undeclared in the overriden method.
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::_get()
	 * @used-by \Df\StripeClone\Facade\Customer::get()
	 * @param string $id
	 * @return C|null
	 */
	protected function _get($id) {
		# 2017-02-18
		# К сожалению, нельз просто написать:
		# return ($c = C::retrieve($id))->isDestroyed() ? null : $c;}
		# На PHP 5.6 будет сбой: [E_PARSE] syntax error, unexpected '->' (T_OBJECT_OPERATOR)
		$c = C::retrieve($id); /** @var C $c */
		return $c->isDestroyed() ? null : $c;
	}

	/**
	 * 2017-02-11 Ключ $o['cards']['data'] присутствует в объекте даже при отсутствии карт.
	 * 2023-01-06 We can not declare the argument's type because it is undeclared in the overriden method.
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::cardsData()
	 * @used-by self::cardAdd()
	 * @used-by \Df\StripeClone\Facade\Customer::cards()
	 * @param C $c
	 * @return array(array(string => string))
	 * @see \Dfe\Omise\Facade\Charge::cardData()
	 */
	protected function cardsData($c):array {return $c['cards']['data'];}
}
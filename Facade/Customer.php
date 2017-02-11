<?php
namespace Dfe\Omise\Facade;
use Dfe\Omise\Card;
use OmiseCustomer as C;
// 2017-02-10
final class Customer extends \Df\StripeClone\Facade\Customer {
	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::cardAdd()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @param C $c
	 * @param string $token
	 * @return string
	 */
	public function cardAdd($c, $token) {
		$c->update(['card' => $token]);
		return df_last($c['cards']['data'])['id'];
	}

	/**
	 * 2017-02-10
	 * Ключ $o['cards']['data'] присутствует в объекте даже при отсутствии карт.
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::cards()
	 * @used-by \Df\StripeClone\ConfigProvider::cards()
	 * @param C $c
	 * @return array(string => string)
	 * [card ID => card label]
	 */
	public function cards($c) {return array_map(function(array $card) {return [
		'id' => $card['id'], 'label' => (string)(new Card($card))
	];}, $c['cards']['data']);}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::create()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @param array(string => mixed) $p
	 * @return C
	 */
	public function create(array $p) {return C::create($p);}

	/**
	 * 2017-02-10
	 * https://github.com/omise/omise-php/issues/43
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::get()
	 * @used-by \Df\StripeClone\ConfigProvider::cards()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @param int $id
	 * @return C
	 */
	public function get($id) {
		/** @var C $c */
		$c = C::retrieve($id);
		return $c->isDestroyed() ? null : $c;
	}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Customer::id()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @param C $c
	 * @return string
	 */
	public function id($c) {return $c['id'];}
}
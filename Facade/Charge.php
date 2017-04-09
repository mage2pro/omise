<?php
namespace Dfe\Omise\Facade;
use Dfe\Omise\Exception\Charge as E;
use Magento\Sales\Model\Order\Payment as OP;
use OmiseCharge as C;
use OmiseRefund as R;
// 2017-02-10
final class Charge extends \Df\StripeClone\Facade\Charge {
	/**
	 * 2017-02-10
	 * https://www.omise.co/charges-api#charges-capture
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::capturePreauthorized()
	 * @used-by \Df\StripeClone\Method::charge()
	 * @param string $id
	 * @param int|float $a В формате и валюте ПС. Значение готово для применения в запросе API.
	 * @return C
	 */
	function capturePreauthorized($id, $a) {return C::retrieve($id)->capture();}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::create()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param array(string => mixed) $p
	 * @return C
	 */
	function create(array $p) {return E::assert(C::create($p), $p);}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::id()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param C $c
	 * @return string
	 */
	function id($c) {return $c['id'];}

	/**
	 * 2017-02-12
	 * Returns the path to the bank card information
	 * in a charge converted to an array by @see \Dfe\Omise\Facade\O::toArray()
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::pathToCard()
	 * @used-by \Df\StripeClone\Block\Info::prepare()
	 * @return string
	 */
	function pathToCard() {return 'card';}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::refund()
	 * @used-by void
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param string $id
	 * @param float $a
	 * В формате и валюте платёжной системы.
	 * Значение готово для применения в запросе API.
	 * @return R
	 */
	function refund($id, $a) {return C::retrieve($id)->refunds()->create(['amount' => $a]);}

	/**
	 * 2017-02-10
	 * Reverse an uncaptured charge: https://www.omise.co/charges-api#charges-reverse
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::void()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param string $id
	 * @return C
	 */
	function void($id) {
		/** @var C $result */
		$result = C::retrieve($id);
		$result->reverse();
		return $result;
	}

	/**
	 * 2017-02-11
	 * Информация о банковской карте.
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::cardData()
	 * @used-by \Df\StripeClone\Facade\Charge::card()
	 * @param C $c
	 * @return array(string => string)
	 * @see \Dfe\Omise\Facade\Customer::cardsData()
	 */
	protected function cardData($c) {return $c['card'];}
}
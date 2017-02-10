<?php
namespace Dfe\Stripe\Facade;
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
	 * @return C
	 */
	public function capturePreauthorized($id) {return C::retrieve($id)->capture();}

	/**
	 * 2017-02-11
	 * Информация о банковской карте.
	 *	{
	 *		"object": "card",
	 *		"id": "card_test_560jgvu90914d44h1vx",
	 *		"livemode": false,
	 *		"location": "/customers/cust_test_560jgw6s43s7i4ydd8r/cards/card_test_560jgvu90914d44h1vx",
	 *		"country": "us",
	 *		"city": null,
	 *		"postal_code": null,
	 *		"financing": "",
	 *		"bank": "",
	 *		"last_digits": "4444",
	 *		"brand": "MasterCard",
	 *		"expiration_month": 7,
	 *		"expiration_year": 2019,
	 *		"fingerprint": "/uCzRPQQRUDr8JvGUjKf7Xn10VRJeQ7oBZ1Zt7gLvWs=",
	 *		"name": "DMITRY FEDYUK",
	 *		"security_code_check": true,
	 *		"created": "2016-11-15T22:00:49Z"
	 *	}
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::card()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param C $c
	 * @return array(string => string)
	 */
	public function card($c) {/** @var array(string => string) $a */ $a = $c['card']; return [
		OP::CC_LAST_4 => $a['last_digits'], OP::CC_TYPE => $a['brand']
	];}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::create()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param array(string => mixed) $p
	 * @return C
	 */
	public function create(array $p) {return E::assert(C::create($p), $p);}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::id()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param C $c
	 * @return string
	 */
	public function id($c) {return $c['id'];}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::refund()
	 * @used-by void
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param string $id
	 * @param float $amount
	 * В формате и валюте платёжной системы.
	 * Значение готово для применения в запросе API.
	 * @return R
	 */
	public function refund($id, $amount) {return C::retrieve($id)->refunds()->create([
		'amount' => $amount
	]);}

	/**
	 * 2017-02-10
	 * Reverse an uncaptured charge: https://www.omise.co/charges-api#charges-reverse
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::void()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param string $id
	 * @return C
	 */
	public function void($id) {
		/** @var C $result */
		$result = C::retrieve($id);
		$result->reverse();
		return $result;
	}
}
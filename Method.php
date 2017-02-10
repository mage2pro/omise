<?php
// 2016-11-10
namespace Dfe\Omise;
use Df\Core\Exception as DFE;
use Dfe\Omise\Api\O as AO;
use Dfe\Omise\Exception\Charge as ECharge;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/** @method Settings s() */
final class Method extends \Df\StripeClone\Method {
	/**
	 * 2016-11-18
	 * @override
	 * A partial capture is not supported by Omise: https://www.omise.co/charges-api#charges-capture
	 * Interestinly, a partial refund is suported: https://www.omise.co/refunds-api#refunds-create
	 *
	 * 2017-02-08
	 * false и так является значением по умолчанию в родителькском методе:
	 * https://github.com/mage2pro/core/blob/1.12.13/Payment/Method.php?ts=4#L338
	 * Однако я явно объявляю здесь свой метод, чтобы явно подчеркнуть,
	 * что Omise не поддерживает эту функцию.
	 *
	 * @see \Df\Payment\Method::canCapturePartial()
	 * @return bool
	 */
	public function canCapturePartial() {return false;}

	/**
	 * 2016-12-24
	 * @override
	 * @see \Df\Payment\Method::_3dsNeed()
	 * @used-by \Df\Payment\Method::getConfigPaymentAction()
	 * @return bool
	 */
	protected function _3dsNeed() {return $this->s()->_3DS();}

	/**
	 * 2017-01-12
	 * @override
	 * https://mage2.pro/t/2460
	 * @see \Df\StripeClone\Method::_3dsNeedForCharge()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param object $charge
	 * @return bool
	 */
	protected function _3dsNeedForCharge($charge) {return
		$charge['authorize_uri'] && self::S_PENDING === $charge['status']
	;}

	/**
	 * 2016-12-24
	 * @override
	 * @see \Df\Payment\Method::_3dsUrl()
	 * @used-by \Df\Payment\Method::getConfigPaymentAction()
	 * @param float $amount
	 * @param bool $capture
	 * @return string
	 * An example of result: http://api.omise.co/payments/paym_test_56fuvl1ih89gj1kjzid/authorize
	 */
	protected function _3dsUrl($amount, $capture) {return
		// 2016-12-24
		// «Url for charge authorization using 3-D Secure. Only if return_uri was set.»
		// https://www.omise.co/charges-api
		$this->chargeNew($amount, $capture)['authorize_uri']
	;}

	/**
	 * 2016-11-15
	 * https://www.omise.co/currency-and-amount
	 * 2017-02-08
	 * [Omise] What are the minimum and maximum amount limitations on a single payment?
	 * https://mage2.pro/t/2691
	 * @override
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by isAvailable()
	 * @return array(string => int[])
	 */
	protected function amountLimits() {return ['THB' => [20, 1000000], 'JPY' => [100, 999999]];}

	/**
	 * 2016-12-28
	 * Информация о банковской карте.
		{
			"object": "card",
			"id": "card_test_560jgvu90914d44h1vx",
			"livemode": false,
			"location": "/customers/cust_test_560jgw6s43s7i4ydd8r/cards/card_test_560jgvu90914d44h1vx",
			"country": "us",
			"city": null,
			"postal_code": null,
			"financing": "",
			"bank": "",
			"last_digits": "4444",
			"brand": "MasterCard",
			"expiration_month": 7,
			"expiration_year": 2019,
			"fingerprint": "/uCzRPQQRUDr8JvGUjKf7Xn10VRJeQ7oBZ1Zt7gLvWs=",
			"name": "DMITRY FEDYUK",
			"security_code_check": true,
			"created": "2016-11-15T22:00:49Z"
		}
	 * @override
	 * @see \Df\StripeClone\Method::apiCardInfo()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param \OmiseCharge $charge
	 * @return array(string => string)
	 */
	protected function apiCardInfo($charge) {
		/** @var array(string => string) $c */
		$c = $charge['card'];
		return [OP::CC_LAST_4 => $c['last_digits'], OP::CC_TYPE => $c['brand']];
	}

	/**
	 * 2016-12-27
	 * @override
	 * @see \Df\StripeClone\Method::responseToArray()
	 * @used-by \Df\StripeClone\Method::transInfo()
	 * @param \OmiseApiResource $response
	 * @return array(string => mixed)
	 */
	protected function responseToArray($response) {return AO::_values($response);}

	/**
	 * 2016-12-26
	 * @override
	 * @see \Df\StripeClone\Method::transUrlBase()
	 * @used-by \Df\StripeClone\Method::transUrl()
	 * @param T $t
	 * @return string
	 */
	protected function transUrlBase(T $t) {return df_cc_path(
		'https://dashboard.omise.co',
		df_trans_is_test($t, 'test', 'live')
		,dfa(['refund' => 'refunds'], $t->getTxnType(), 'charges')
	);}

	/**
	 * 2017-01-15
	 * @used-by _3dsNeedForCharge()
	 * @used-by \Dfe\Omise\Webhook\Charge\Complete::isPending()
	 */
	const S_PENDING = 'pending';

	/**
	 * 2017-01-15
	 * @used-by \Dfe\Omise\Webhook\Charge\Complete::isPending()
	 */
	const S_SUCCESSFUL = 'successful';
}
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
class Method extends \Df\StripeClone\Method {
	/**
	 * 2016-11-18
	 * @override
	 * A partial capture is not supported by Omise: https://www.omise.co/charges-api#charges-capture
	 * Interestinly, a partial refund is suported: https://www.omise.co/refunds-api#refunds-create
	 * @see \Df\Payment\Method::canCapturePartial()
	 * @return bool
	 */
	public function canCapturePartial() {return false;}

	/**
	 * 2016-11-13
	 * https://www.omise.co/refunds-api#refunds-create
	 * @override
	 * @see \Df\Payment\Method::canRefundPartialPerInvoice()
	 * @return bool
	 */
	public function canRefundPartialPerInvoice() {return true;}

	/**
	 * 2016-12-24
	 * @override
	 * @see \Df\Payment\Method::_3dsNeed()
	 * @used-by \Df\Payment\Method::getConfigPaymentAction()
	 * @return bool
	 */
	final protected function _3dsNeed() {return $this->s()->_3DS();}

	/**
	 * 2017-01-12
	 * @override
	 * https://mage2.pro/t/2460
	 * @see \Df\StripeClone\Method::_3dsNeedForCharge()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param object $charge
	 * @return bool
	 */
	final protected function _3dsNeedForCharge($charge) {return
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
	final protected function _3dsUrl($amount, $capture) {return
		// 2016-12-24
		// «Url for charge authorization using 3-D Secure. Only if return_uri was set.»
		// https://www.omise.co/charges-api
		$this->chargeNew($amount, $capture)['authorize_uri']
	;}

	/**
	 * 2016-11-15
	 * https://www.omise.co/currency-and-amount
	 * @override
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by isAvailable()
	 * @return array(string => array(int|float))
	 */
	final protected function amountLimits() {return ['THB' => [20, 1000000], 'JPY' => [100, 999999]];}

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
	final protected function apiCardInfo($charge) {
		/** @var array(string => string) $c */
		$c = $charge['card'];
		return [OP::CC_LAST_4 => $c['last_digits'], OP::CC_TYPE => $c['brand']];
	}

	/**
	 * 2016-12-28
	 * https://www.omise.co/charges-api#charges-capture
	 * @override
	 * @see \Df\StripeClone\Method::apiChargeCapturePreauthorized()
	 * @used-by \Df\StripeClone\Method::charge()
	 * @param string $chargeId
	 * @return \OmiseCharge
	 */
	final protected function apiChargeCapturePreauthorized($chargeId) {return
		\OmiseCharge::retrieve($chargeId)->capture()
	;}

	/**
	 * 2016-12-28
	 * @override
	 * @see \Df\StripeClone\Method::apiChargeCreate()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param array(string => mixed) $params
	 * @return \OmiseCharge
	 */
	final protected function apiChargeCreate(array $params) {return
		ECharge::assert(\OmiseCharge::create($params), $params)
	;}

	/**
	 * 2016-12-28
	 * @override
	 * @see \Df\StripeClone\Method::apiChargeId()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param \OmiseCharge $charge
	 * @return string
	 */
	final protected function apiChargeId($charge) {return $charge['id'];}

	/**
	 * 2017-01-19
	 * Пример результата: «trxn_test_56psvralu7nzx74ytit».
	 * @override
	 * @see \Df\StripeClone\Method::apiTransId()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param object $response
	 * @return string
	 */
	final protected function apiTransId($response) {return $response['transaction'];}

	/**
	 * 2016-12-27
	 * @override
	 * @see \Df\StripeClone\Method::responseToArray()
	 * @used-by \Df\StripeClone\Method::transInfo()
	 * @param \OmiseApiResource $response
	 * @return array(string => mixed)
	 */
	final protected function responseToArray($response) {return AO::_values($response);}

	/**
	 * 2017-01-19
	 * @override
	 * @see \Df\StripeClone\Method::scRefund()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param string $chargeId
	 * @param float $amount
	 * В формате и валюте платёжной системы.
	 * Значение готово для применения в запросе API.
	 * @return \OmiseCharge
	 */
	final protected function scRefund($chargeId, $amount) {return
		\OmiseCharge::retrieve($chargeId)->refunds()->create(['amount' => $amount])
	;}

	/**
	 * 2017-01-19
	 * @override
	 * @see \Df\StripeClone\Method::scVoid()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param string $chargeId
	 * @return \OmiseCharge
	 */
	final protected function scVoid($chargeId) {
		/** @var \OmiseCharge $result */
		$result = \OmiseCharge::retrieve($chargeId);
		// 2016-11-18
		// Reverse an uncaptured charge: https://www.omise.co/charges-api#charges-reverse
		$result->reverse();
		return $result;
	}

	/**
	 * 2016-12-26
	 * @override
	 * @see \Df\StripeClone\Method::transUrlBase()
	 * @used-by \Df\StripeClone\Method::transUrl()
	 * @param T $t
	 * @return string
	 */
	final protected function transUrlBase(T $t) {return df_cc_path(
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
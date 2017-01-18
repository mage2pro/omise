<?php
namespace Dfe\Omise\Webhook\Refund;
use Df\StripeClone\WebhookStrategy\Charge\Refunded;
use Dfe\Omise\Method as M;
/**
 * 2017-01-17
 * Оповещение «refund.create» приходит
 * при выполнении операции «refund» из административного интерфейса Omise.
 * https://www.omise.co/api-webhooks#refund-events
 *
 * Пример запроса:
	{
		"object": "event",
		"id": "evnt_test_56p1c9obq5fr6dlkhge",
		"livemode": false,
		"location": "/events/evnt_test_56p1c9obq5fr6dlkhge",
		"key": "refund.create",
		"created": "2017-01-17T13:07:24Z",
		"data": {
			"object": "refund",
			"id": "rfnd_test_56p1c9mjwmxaaxm4lbg",
			"location": "/charges/chrg_test_56p1bh0a41rrhvbl3ob/refunds/rfnd_test_56p1c9mjwmxaaxm4lbg",
			"amount": 305660,
			"currency": "thb",
			"voided": true,
			"charge": "chrg_test_56p1bh0a41rrhvbl3ob",
			"transaction": "trxn_test_56p1c9n3dt48q7t83bk",
			"created": "2017-01-17T20:07:24+07:00"
		}
	}
 */
final class Create extends \Dfe\Omise\Webhook\Charge implements \Df\StripeClone\Webhook\IRefund {
	/**
	 * 2017-01-17
	 * В валюте заказа (платежа).
	 * @override
	 * @see \Df\StripeClone\Webhook\IRefund::amount()
	 * @used-by \Df\StripeClone\WebhookStrategy\Charge\Refunded::handle()
	 * @return int
	 */
	final public function amount() {return $this->ro('amount');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::currentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @used-by \Df\StripeClone\WebhookStrategy::currentTransactionType()
	 * @return string
	 */
	final public function currentTransactionType() {return M::T_REFUND;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::idBase()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @return string
	 */
	final protected function idBase() {return $this->ro('id');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Dfe\Omise\Webhook::parentIdRawKey()
	 * @used-by \Df\Payment\Webhook::parentIdRaw()
	 * @return string
	 */
	final protected function parentIdRawKey() {return 'data/charge';}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::parentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::adaptParentId()
	 * @return string
	 */
	final protected function parentTransactionType() {return M::T_CAPTURE;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::strategyC()
	 * @used-by \Df\StripeClone\Webhook::_handle()
	 * @return string
	 */
	final protected function strategyC() {return Refunded::class;}
}
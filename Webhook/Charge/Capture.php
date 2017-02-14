<?php
namespace Dfe\Omise\Webhook\Charge;
use Df\StripeClone\WebhookStrategy\Charge\Captured as Strategy;
use Dfe\Omise\Method as M;
// 2017-01-17
// Оповещение «charge.capture» приходит
// при выполнении операции «capture» из административного интерфейса Omise.
// https://www.omise.co/api-webhooks#charge-events
// 2017-02-14
// An example of this event: https://mage2.pro/t/2746
final class Capture extends \Dfe\Omise\Webhook {
	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::currentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @used-by \Df\StripeClone\WebhookStrategy::currentTransactionType()
	 * @return string
	 */
	function currentTransactionType() {return M::T_CAPTURE;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::parentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::adaptParentId()
	 * @return string
	 */
	protected function parentTransactionType() {return M::T_AUTHORIZE;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::strategyC()
	 * @used-by \Df\StripeClone\Webhook::_handle()
	 * @return string
	 */
	protected function strategyC() {return Strategy::class;}
}
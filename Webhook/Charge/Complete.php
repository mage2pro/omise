<?php
namespace Dfe\Omise\Webhook\Charge;
use Df\StripeClone\Webhook\Charge\CapturedStrategy;
use Dfe\Omise\Method as M;
/**
 * 2017-01-09
 * 2017-01-12
 * Это событие используется только в сценарии 3D Secure
 * и означает успешность завершения проверки 3D Secure:
 * https://www.omise.co/api-webhooks#charge-events
 */
class Complete extends \Dfe\Omise\Webhook\Charge {
	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\Webhook::currentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @return string
	 */
	final protected function currentTransactionType() {return M::T_CAPTURE;}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\Webhook::parentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::adaptParentId()
	 * @return string
	 */
	final protected function parentTransactionType() {return M::T_3DS;}

	/**
	 * 2017-01-12
	 * @override
	 * @see \Df\StripeClone\Webhook::strategyC()
	 * @used-by \Df\StripeClone\Webhook::_handle()
	 * @return string
	 */
	final protected function strategyC() {return CapturedStrategy::class;}
}
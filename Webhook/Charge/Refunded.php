<?php
// 2017-01-09
namespace Dfe\Omise\Webhook\Charge;
class Refunded extends \Dfe\Omise\Webhook\Charge {
	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\Webhook::currentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @return string
	 */
	final protected function currentTransactionType() {return 'refund';}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Dfe\Stripe\Webhook\Charge::parentTransactionType()
	 * @used-by \Dfe\Stripe\Webhook\Charge::adaptParentId()
	 * @return string
	 */
	final protected function parentTransactionType() {return 'capture';}
}
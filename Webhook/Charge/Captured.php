<?php
// 2017-01-09
namespace Dfe\Omise\Webhook\Charge;
class Captured extends \Dfe\Omise\Webhook\Charge {
	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\Webhook::currentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @return string
	 */
	final protected function currentTransactionType() {return 'capture';}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\Webhook::parentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::adaptParentId()
	 * @return string
	 */
	final protected function parentTransactionType() {return 'authorize';}
}
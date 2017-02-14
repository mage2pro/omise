<?php
namespace Dfe\Omise;
/**
 * 2017-01-09
 * @see \Dfe\Omise\Webhook\Charge\Capture
 * @see \Dfe\Omise\Webhook\Charge\Complete
 * @see \Dfe\Omise\Webhook\Refund\Create
 */
abstract class Webhook extends \Df\StripeClone\Webhook {
	/**
	 * 2017-01-12
	 * 2017-02-14
	 * [Omise] An example of the «charge.capture» event (being sent to a webhook)
	 * https://mage2.pro/t/2746
	 * @override
	 * @see \Df\StripeClone\Webhook::roPath()
	 * @used-by \Df\StripeClone\Webhook::ro()
	 * @return string
	 */
	final protected function roPath() {return 'data';}
}
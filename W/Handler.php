<?php
namespace Dfe\Omise\W;
/**
 * 2017-01-09
 * @see \Dfe\Omise\W\Handler\Charge\Capture
 * @see \Dfe\Omise\W\Handler\Charge\Complete
 * @see \Dfe\Omise\W\Handler\Refund\Create
 */
abstract class Handler extends \Df\StripeClone\W\Handler {
	/**
	 * 2017-01-12
	 * 2017-02-14
	 * [Omise] An example of the «charge.capture» event (being sent to a webhook)
	 * https://mage2.pro/t/2746
	 * @override
	 * @see \Df\StripeClone\W\Handler::roPath()
	 * @used-by \Df\StripeClone\W\Handler::ro()
	 * @return string
	 */
	final protected function roPath() {return 'data';}
}
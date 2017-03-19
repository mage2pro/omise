<?php
namespace Dfe\Omise\W;
/**
 * 2017-03-15
 * @see \Dfe\Omise\W\Event\Charge\Capture
 * @see \Dfe\Omise\W\Event\Charge\Complete
 * @see \Dfe\Omise\W\Event\Refund
 */
abstract class Event extends \Df\StripeClone\W\Event {
	/**
	 * 2017-01-12
	 * 2017-02-14
	 * [Omise] An example of the «charge.capture» event (being sent to a webhook)
	 * https://mage2.pro/t/2746
	 * @override
	 * @see \Df\StripeClone\W\Event::roPath()
	 * @used-by \Df\StripeClone\W\Event::k_pid()
	 * @used-by \Df\StripeClone\W\Event::ro()
	 * @return string
	 */
	final protected function roPath() {return 'data';}
}
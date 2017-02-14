<?php
namespace Dfe\Omise;
// 2017-01-09
/** @see \Dfe\Omise\Webhook\Charge */
abstract class Webhook extends \Df\StripeClone\Webhook {
	/**
	 * 2017-01-12
	 * 2017-02-14
	 * [Omise] An example of the «charge.capture» event (being sent to a webhook)
	 * https://mage2.pro/t/2746
	 * @override
	 * @see \Df\Payment\Webhook::parentIdRawKey()
	 * @used-by \Df\Payment\Webhook::parentIdRaw()
	 * @see \Dfe\Omise\Webhook\Refund\Create::parentIdRawKey()
	 * @return string
	 */
	protected function parentIdRawKey() {return 'data/id';}

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
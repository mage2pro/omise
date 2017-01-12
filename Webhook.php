<?php
// 2017-01-09
namespace Dfe\Omise;
abstract class Webhook extends \Df\StripeClone\Webhook {
	/**
	 * 2017-01-12
	 * @override
	 * @see \Df\Payment\Webhook::parentIdRawKey()
	 * @used-by \Df\Payment\Webhook::parentIdRaw()
	 * @return string
	 */
	final protected function parentIdRawKey() {return 'data/id';}

	/**
	 * 2017-01-12
	 * @override
	 * @see \Df\StripeClone\Webhook::roPath()
	 * @used-by \Df\StripeClone\Webhook::ro()
	 * @return string
	 */
	final protected function roPath() {return 'data';}
}
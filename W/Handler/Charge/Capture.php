<?php
namespace Dfe\Omise\W\Handler\Charge;
use \Df\StripeClone\W\Strategy\Captured as Strategy;
// 2017-01-17
// Оповещение «charge.capture» приходит
// при выполнении операции «capture» из административного интерфейса Omise.
// https://www.omise.co/api-webhooks#charge-events
// 2017-02-14
// An example of this event: https://mage2.pro/t/2746
final class Capture extends \Df\StripeClone\W\Handler {
	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Handler::strategyC()
	 * @used-by \Df\StripeClone\W\Handler::_handle()
	 * @return string
	 */
	protected function strategyC() {return Strategy::class;}
}
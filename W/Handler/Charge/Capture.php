<?php
namespace Dfe\Omise\W\Handler\Charge;
use Df\Payment\W\Strategy\CapturePreauthorized as Strategy;
// 2017-01-17
// 2017-08-16 We get the `charge.capture` event when the merchant has just captured a preauthorized payment
// from his Omise dashboard: https://www.omise.co/api-webhooks#charge-events
// 2017-02-14 An example of this event: https://mage2.pro/t/2746
final class Capture extends \Df\Payment\W\Handler {
	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\Payment\W\Handler::strategyC()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @return string
	 */
	protected function strategyC() {return Strategy::class;}
}
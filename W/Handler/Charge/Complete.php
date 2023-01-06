<?php
namespace Dfe\Omise\W\Handler\Charge;
use Df\Payment\W\Strategy\ConfirmPending as A;
use Df\Payment\W\Strategy\CapturePreauthorized as C;
# 2017-01-12
# 2017-08-16
# We get this event when a payment has just been successfully verified by 3D-Secure.
# https://www.omise.co/api-webhooks#charge-events
/** @method \Dfe\Omise\W\Event\Charge\Complete e() */
final class Complete extends \Df\Payment\W\Handler {
	/**
	 * 2017-01-12
	 * @override
	 * @see \Df\Payment\W\Handler::strategyC()
	 * @used-by \Df\Payment\W\Handler::handle()
	 */
	protected function strategyC():string {return $this->e()->isPending() ? A::class : C::class;}
}
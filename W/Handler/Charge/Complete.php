<?php
namespace Dfe\Omise\W\Handler\Charge;
use Df\Payment\W\Strategy\ConfirmPending as A;
use Df\Payment\W\Strategy\CapturePreauthorized as C;
// 2017-01-12
// Это событие используется только в сценарии 3D Secure
// и означает успешность завершения проверки 3D Secure:
// https://www.omise.co/api-webhooks#charge-events
/** @method \Dfe\Omise\W\Event\Charge\Complete e() */
final class Complete extends \Df\StripeClone\W\Handler {
	/**
	 * 2017-01-12
	 * @override
	 * @see \Df\StripeClone\W\Handler::strategyC()
	 * @used-by \Df\StripeClone\W\Handler::_handle()
	 * @return string
	 */
	protected function strategyC() {return $this->e()->isPending() ? A::class : C::class;}
}
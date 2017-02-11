<?php
namespace Dfe\Omise\Webhook\Charge;
use Df\StripeClone\WebhookStrategy\Charge\Authorized;
use Df\StripeClone\WebhookStrategy\Charge\Captured;
use Dfe\Omise\Method as M;
/**
 * 2017-01-09
 * 2017-01-12
 * Это событие используется только в сценарии 3D Secure
 * и означает успешность завершения проверки 3D Secure:
 * https://www.omise.co/api-webhooks#charge-events
 */
final class Complete extends \Dfe\Omise\Webhook\Charge {
	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\Webhook::currentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @used-by \Df\StripeClone\WebhookStrategy::currentTransactionType()
	 * @return string
	 */
	final function currentTransactionType() {return
		$this->isPending() ? M::T_AUTHORIZE : M::T_CAPTURE
	;}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\Webhook::parentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::adaptParentId()
	 * @return string
	 */
	final protected function parentTransactionType() {return M::T_3DS;}

	/**
	 * 2017-01-12
	 * @override
	 * @see \Df\StripeClone\Webhook::strategyC()
	 * @used-by \Df\StripeClone\Webhook::_handle()
	 * @return string
	 */
	final protected function strategyC() {return $this->isPending() ? Authorized::class : Captured::class;}

	/**
	 * 2017-01-15
	 * Здесь, в оповещении «charge.complete», успешное состояние charge зависит указанного нами ранее
	 * при создании charge в методе @see \Dfe\Omise\Charge::_request() значения флага «capture»:
	 * https://code.dmitry-fedyuk.com/m2e/omise/blob/1.1.2/Charge.php#L27
	 * Если мы этот флаг устанавливали, то здесь успешным состоянием charge будет «successful».
	 * Если мы этот флаг не устанавливали, то здесь успешным состоянием charge будет «pending».
	 * https://mage2.pro/tags/omise-charge-status
	 * https://mage2.pro/t/2460
	 * @used-by currentTransactionType()
	 * @used-by strategyC()
	 * @return bool
	 */
	private function isPending() {return dfc($this, function() {return
		M::S_PENDING === df_assert_in($this->ro('status'), [M::S_PENDING, M::S_SUCCESSFUL])
	;});}
}
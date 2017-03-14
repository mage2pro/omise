<?php
namespace Dfe\Omise\W\Handler\Charge;
use Df\StripeClone\W\Strategy\Charge\Authorized;
use Df\StripeClone\W\Strategy\Charge\Captured;
use Dfe\Omise\Method as M;
// 2017-01-12
// Это событие используется только в сценарии 3D Secure
// и означает успешность завершения проверки 3D Secure:
// https://www.omise.co/api-webhooks#charge-events
final class Complete extends \Dfe\Omise\W\Handler {
	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\W\Handler::currentTransactionType()
	 * @used-by \Df\StripeClone\W\Handler::id()
	 * @used-by \Df\StripeClone\W\Strategy::currentTransactionType()
	 * @return string
	 */
	function currentTransactionType() {return $this->isPending() ? M::T_AUTHORIZE : M::T_CAPTURE;}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\W\Handler::parentTransactionType()
	 * @used-by \Df\StripeClone\W\Handler::adaptParentId()
	 * @return string
	 */
	protected function parentTransactionType() {return M::T_3DS;}

	/**
	 * 2017-01-12
	 * @override
	 * @see \Df\StripeClone\W\Handler::strategyC()
	 * @used-by \Df\StripeClone\W\Handler::_handle()
	 * @return string
	 */
	protected function strategyC() {return $this->isPending() ? Authorized::class : Captured::class;}

	/**
	 * 2017-01-15
	 * Здесь, в оповещении «charge.complete», успешное состояние charge зависит указанного нами ранее
	 * при создании charge в методе @see \Dfe\Omise\Charge::pCharge() значения флага «capture»:
	 * https://github.com/mage2pro/omise/tree/1.1.2/Charge.php#L27
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
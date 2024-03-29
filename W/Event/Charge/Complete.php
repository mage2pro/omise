<?php
namespace Dfe\Omise\W\Event\Charge;
use Dfe\Omise\Method as M;
# 2017-03-15
final class Complete extends \Dfe\Omise\W\Event {
	/**
	 * 2017-01-15
	 * Здесь, в оповещении «charge.complete», успешное состояние charge зависит указанного нами ранее
	 * при создании charge в методе @see \Dfe\Omise\P\Charge::p() значения флага «capture»:
	 * https://github.com/mage2pro/omise/tree/1.1.2/Charge.php#L27
	 * Если мы этот флаг устанавливали, то здесь успешным состоянием charge будет «successful».
	 * Если мы этот флаг не устанавливали, то здесь успешным состоянием charge будет «pending».
	 * https://mage2.pro/tags/omise-charge-status
	 * https://mage2.pro/t/2460
	 * @used-by self::ttCurrent()
	 * @used-by \Dfe\Omise\W\Handler\Charge\Complete::strategyC()
	 */
	function isPending():bool {return dfc($this, function():bool {return
		M::S_PENDING === df_assert_in($this->ro('status'), [M::S_PENDING, M::S_SUCCESSFUL])
	;});}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Payment\W\Event::ttCurrent()
	 * @used-by \Df\StripeClone\W\Nav::id()
	 * @used-by \Df\Payment\W\Strategy\ConfirmPending::_handle()
	 */
	function ttCurrent():string {return $this->isPending() ? self::T_AUTHORIZE : self::T_CAPTURE;}
	
	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\StripeClone\W\Event::ttParent()
	 * @used-by \Df\StripeClone\W\Nav::pidAdapt()
	 */
	function ttParent():string {return self::T_3DS;}
}
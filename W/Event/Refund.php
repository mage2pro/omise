<?php
namespace Dfe\Omise\W\Event;
# 2017-03-15
final class Refund extends \Dfe\Omise\W\Event {
	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Event::idBase()
	 * @used-by \Df\StripeClone\W\Nav::id()
	 */
	function idBase():string {return $this->ro('id');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\Payment\W\Event::ttCurrent()
	 * @used-by \Df\StripeClone\W\Nav::id()
	 * @used-by \Df\Payment\W\Strategy\ConfirmPending::_handle()
	 */
	function ttCurrent():string {return self::T_REFUND;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Event::ttParent()
	 * @used-by \Df\StripeClone\W\Nav::pidAdapt()
	 */
	function ttParent():string {return self::T_CAPTURE;}
	
	/**
	 * 2017-02-14
	 * @override
	 * @see \Df\StripeClone\W\Event::k_pidSuffix()
	 * @used-by \Df\StripeClone\W\Event::k_pid()
	 */
	protected function k_pidSuffix():string {return 'charge';}
}
<?php
namespace Dfe\Omise\W\Event;
use Dfe\Omise\Method as M;
// 2017-03-15
final class Refund extends \Dfe\Omise\W\Event {
	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Event::idBase()
	 * @used-by \Df\StripeClone\W\Nav::id()
	 * @return string
	 */
	function idBase() {return $this->ro('id');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Event::ttCurrent()
	 * @used-by \Df\StripeClone\W\Event::id()
	 * @used-by \Df\StripeClone\W\Strategy\Charge::action()
	 * @return string
	 */
	function ttCurrent() {return M::T_REFUND;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Event::ttParent()
	 * @used-by \Df\StripeClone\W\Nav::pidAdapt()
	 * @return string
	 */
	function ttParent() {return M::T_CAPTURE;}
	
	/**
	 * 2017-02-14
	 * @override
	 * @see \Df\StripeClone\W\Event::k_pidSuffix()
	 * @used-by \Df\StripeClone\W\Event::k_pid()
	 * @return string
	 */
	protected function k_pidSuffix() {return 'charge';}
}
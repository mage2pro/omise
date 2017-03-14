<?php
namespace Dfe\Omise\W\Handler\Charge;
use Df\StripeClone\W\Strategy\Charge\Captured as Strategy;
use Dfe\Omise\Method as M;
// 2017-01-17
// Оповещение «charge.capture» приходит
// при выполнении операции «capture» из административного интерфейса Omise.
// https://www.omise.co/api-webhooks#charge-events
// 2017-02-14
// An example of this event: https://mage2.pro/t/2746
final class Capture extends \Dfe\Omise\W\Handler {
	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Handler::currentTransactionType()
	 * @used-by \Df\StripeClone\W\Handler::id()
	 * @used-by \Df\StripeClone\W\Strategy::currentTransactionType()
	 * @return string
	 */
	function currentTransactionType() {return M::T_CAPTURE;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Handler::parentTransactionType()
	 * @used-by \Df\StripeClone\W\Handler::adaptParentId()
	 * @return string
	 */
	protected function parentTransactionType() {return M::T_AUTHORIZE;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Handler::strategyC()
	 * @used-by \Df\StripeClone\W\Handler::_handle()
	 * @return string
	 */
	protected function strategyC() {return Strategy::class;}
}
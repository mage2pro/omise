<?php
namespace Dfe\Omise\W\Handler\Refund;
use Df\StripeClone\W\Strategy\Charge\Refunded as Strategy;
// 2017-01-17
// Оповещение «refund.create» приходит
// при выполнении операции «refund» из административного интерфейса Omise.
// https://www.omise.co/api-webhooks#refund-events
// 2017-02-14
// An example of this event: https://mage2.pro/t/2748
final class Create extends \Df\StripeClone\W\Handler implements \Df\StripeClone\W\IRefund {
	/**
	 * 2017-01-17
	 * В валюте заказа (платежа), в формате платёжной системы (копейках).
	 * @override
	 * @see \Df\StripeClone\W\IRefund::amount()
	 * @used-by \Df\StripeClone\W\Strategy\Refunded::_handle()
	 * @return int
	 */
	function amount() {return $this->e()->ro('amount');}

	/**
	 * 2017-01-19
	 * 2017-02-14
	 * Метод должен вернуть идентификатор операции (не платежа!) в платёжной системе.
	 * Он нужен нам для избежания обработки оповещений о возвратах, инициированных нами же
	 * из административной части Magento: @see \Df\StripeClone\Method::_refund()
	 * Это должен быть тот же самый идентификатор,
	 * который возвращает @see \Dfe\Omise\Facade\Refund::transId()
	 * @override
	 * @see \Df\StripeClone\W\IRefund::eTransId()
	 * @used-by \Df\StripeClone\W\Strategy\Refunded::_handle()
	 * @return string
	 */
	function eTransId() {return $this->e()->ro('transaction');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\W\Handler::strategyC()
	 * @used-by \Df\StripeClone\W\Handler::_handle()
	 * @return string
	 */
	protected function strategyC() {return Strategy::class;}
}
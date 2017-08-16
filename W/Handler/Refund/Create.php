<?php
namespace Dfe\Omise\W\Handler\Refund;
/**
 * 2017-01-17
 * 2017-02-14 An example of this event: https://mage2.pro/t/2748
 * 2017-08-16
 * We get this event when the merchant has just refunded a payment from his Omise dashboard.
 * https://www.omise.co/api-webhooks#refund-events
 * @method \Dfe\Omise\W\Event\Refund e()
 */
final class Create extends \Df\Payment\W\Handler implements \Df\Payment\W\IRefund {
	/**
	 * 2017-01-17 В валюте заказа (платежа), в формате платёжной системы (копейках).
	 * @override
	 * @see \Df\Payment\W\IRefund::amount()
	 * @used-by \Df\Payment\W\Strategy\Refund::_handle()
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
	 * @see \Df\Payment\W\IRefund::eTransId()
	 * @used-by \Df\Payment\W\Strategy\Refund::_handle()
	 * @return string
	 */
	function eTransId() {return $this->e()->ro('transaction');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\Payment\W\Handler::strategyC()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @return string
	 */
	protected function strategyC() {return \Df\Payment\W\Strategy\Refund::class;}
}
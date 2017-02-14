<?php
namespace Dfe\Omise\Webhook\Refund;
use Df\StripeClone\WebhookStrategy\Charge\Refunded as Strategy;
use Dfe\Omise\Method as M;
// 2017-01-17
// Оповещение «refund.create» приходит
// при выполнении операции «refund» из административного интерфейса Omise.
// https://www.omise.co/api-webhooks#refund-events
// 2017-02-14
// An example of this event: https://mage2.pro/t/2748
final class Create extends \Dfe\Omise\Webhook implements \Df\StripeClone\Webhook\IRefund {
	/**
	 * 2017-01-17
	 * В валюте заказа (платежа), в формате платёжной системы (копейках).
	 * @override
	 * @see \Df\StripeClone\Webhook\IRefund::amount()
	 * @used-by \Df\StripeClone\WebhookStrategy\Charge\Refunded::handle()
	 * @return int
	 */
	function amount() {return $this->ro('amount');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::currentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @used-by \Df\StripeClone\WebhookStrategy::currentTransactionType()
	 * @return string
	 */
	function currentTransactionType() {return M::T_REFUND;}

	/**
	 * 2017-01-19
	 * 2017-02-14
	 * Метод должен вернуть идентификатор операции (не платежа!) в платёжной системе.
	 * Он нужен нам для избежания обработки оповещений о возвратах, инициированных нами же
	 * из административной части Magento: @see \Df\StripeClone\Method::_refund()
	 * Это должен быть тот же самый идентификатор,
	 * который возвращает @see \Dfe\Omise\Facade\Refund::transId()
	 * @override
	 * @see \Df\StripeClone\Webhook\IRefund::eTransId()
	 * @used-by \Df\StripeClone\WebhookStrategy\Charge\Refunded::handle()
	 * @return string
	 */
	function eTransId() {return $this->ro('transaction');}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::idBase()
	 * @used-by \Df\StripeClone\Webhook::id()
	 * @return string
	 */
	protected function idBase() {return $this->ro('id');}

	/**
	 * 2017-02-14
	 * @override
	 * @see \Df\StripeClone\Webhook::parentIdRawKeySuffix()
	 * @used-by \Df\StripeClone\Webhook::parentIdRawKey()
	 * @return string
	 */
	protected function parentIdRawKeySuffix() {return 'charge';}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::parentTransactionType()
	 * @used-by \Df\StripeClone\Webhook::adaptParentId()
	 * @return string
	 */
	protected function parentTransactionType() {return M::T_CAPTURE;}

	/**
	 * 2017-01-17
	 * @override
	 * @see \Df\StripeClone\Webhook::strategyC()
	 * @used-by \Df\StripeClone\Webhook::_handle()
	 * @return string
	 */
	protected function strategyC() {return Strategy::class;}
}
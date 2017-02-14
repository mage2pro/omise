<?php
namespace Dfe\Omise\Facade;
use OmiseCharge as C;
use OmiseRefund as R;
// 2017-02-10
final class Refund extends \Df\StripeClone\Facade\Refund {
	/**
	 * 2017-02-10
	 * Метод должен вернуть идентификатор операции (не платежа!) в платёжной системе.
	 * Мы записываем его в БД и затем при обработке оповещений от платёжной системы
	 * смотрим, не было ли это оповещение инициировано нашей же операцией,
	 * и если было, то не обрабатываем его повторно.
	 * 
	 * 2017-02-14
	 * Этот же идентификатор должен возвращать @see \Dfe\Omise\Webhook\Refund\Create::eTransId()
	 *
	 * @override
	 * @see \Df\StripeClone\Facade\Refund::transId()
	 * @used-by \Df\StripeClone\Method::_refund()
	 * @param C|R $r
	 * $r имеет класс R в операции refund и класс C в операции void.
	 * @return string
	 * Пример результата: «trxn_test_56psvralu7nzx74ytit».
	 */
	function transId($r) {return $r['transaction'];}
}
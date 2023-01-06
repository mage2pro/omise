<?php
# 2016-11-10
namespace Dfe\Omise;
use Df\Core\Exception as DFE;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/** @method Settings s() */
final class Method extends \Df\StripeClone\Method {
	/**
	 * 2016-11-18
	 * @override
	 * A partial capture is not supported by Omise: https://www.omise.co/charges-api#charges-capture
	 * Interestinly, a partial refund is suported: https://www.omise.co/refunds-api#refunds-create
	 * 2017-02-08
	 * false и так является значением по умолчанию в родителькском методе:
	 * https://github.com/mage2pro/core/blob/1.12.13/Payment/Method.php?ts=4#L338
	 * Однако я явно объявляю здесь свой метод, чтобы явно подчеркнуть,
	 * что Omise не поддерживает эту функцию.
	 * @see \Df\Payment\Method::canCapturePartial()
	 */
	function canCapturePartial():bool {return false;}

	/**
	 * 2016-11-15 https://www.omise.co/currency-and-amount
	 * 2017-02-08
	 * «What are the minimum and maximum amount limitations on a single payment?» https://mage2.pro/t/2691
	 * @override
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by self::isAvailable()
	 * @return array(string => int[])
	 */
	protected function amountLimits():array {return ['THB' => [20, 1000000], 'JPY' => [100, 999999]];}

	/**
	 * 2017-01-12 https://mage2.pro/t/2460
	 * 2022-11-17
	 * `object` as an argument type is not supported by PHP < 7.2:
	 * https://github.com/mage2pro/core/issues/174#user-content-object
	 * @override
	 * @see \Df\StripeClone\Method::redirectNeeded()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param object $c
	 */
	protected function redirectNeeded($c):bool {return $c['authorize_uri'] && self::S_PENDING === $c['status'];}

	/**
	 * 2016-12-26
	 * @override
	 * @see \Df\StripeClone\Method::transUrlBase()
	 * @used-by \Df\StripeClone\Method::transUrl()
	 */
	protected function transUrlBase(T $t):string {return df_cc_path(
		'https://dashboard.omise.co',
		df_trans_is_test($t, 'test', 'live')
		,dfa(['refund' => 'refunds'], $t->getTxnType(), 'charges')
	);}

	/**
	 * 2017-01-15
	 * @used-by self::redirectNeeded()
	 * @used-by \Dfe\Omise\W\Handler\Charge\Complete::isPending()
	 */
	const S_PENDING = 'pending';

	/**
	 * 2017-01-15
	 * @used-by \Dfe\Omise\W\Handler\Charge\Complete::isPending()
	 */
	const S_SUCCESSFUL = 'successful';
}
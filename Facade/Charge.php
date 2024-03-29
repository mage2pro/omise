<?php
namespace Dfe\Omise\Facade;
use Dfe\Omise\Exception\Charge as E;
use Magento\Sales\Model\Order\Payment as OP;
use OmiseCharge as C;
use OmiseRefund as R;
# 2017-02-10
final class Charge extends \Df\StripeClone\Facade\Charge {
	/**
	 * 2017-02-10 https://www.omise.co/charges-api#charges-capture
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::capturePreauthorized()
	 * @used-by \Df\StripeClone\Method::charge()
	 * @param int|float $a
	 * The $a value is already converted to the PSP currency and formatted according to the PSP requirements.
	 */
	function capturePreauthorized(string $id, $a):C {return C::retrieve($id)->capture();}

	/**
	 * 2017-02-10
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::create()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param array(string => mixed) $p
	 */
	function create(array $p):C {return E::assert(C::create($p), $p);}

	/**
	 * 2017-02-10
	 * 2023-01-06 We can not declare the argument's type because it is undeclared in the overriden method.
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::id()
	 * @used-by \Df\StripeClone\Method::chargeNew()
	 * @param C $c
	 */
	function id($c):string {return $c['id'];}

	/**
	 * 2017-02-12
	 * Returns the path to the bank card information
	 * in a charge converted to an array by @see \Dfe\Omise\Facade\O::toArray()
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::pathToCard()
	 * @used-by \Df\StripeClone\Block\Info::cardDataFromChargeResponse()
	 * @used-by \Df\StripeClone\Facade\Charge::cardData()
	 */
	function pathToCard():string {return 'card';}

	/**
	 * 2017-02-10
	 * 2022-12-19 The $a value is already converted to the PSP currency and formatted according to the PSP requirements.
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::refund()
	 * @used-by \Df\StripeClone\Method::_refund()
	 */
	function refund(string $id, int $a):R {return C::retrieve($id)->refunds()->create(['amount' => $a]);}

	/**
	 * 2017-02-10
	 * Reverse an uncaptured charge: https://www.omise.co/charges-api#charges-reverse
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::void()
	 * @used-by \Df\StripeClone\Method::_refund()
	 */
	function void(string $id):C {/** @var C $r */ $r = C::retrieve($id); $r->reverse(); return $r;}

	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Facade\Charge::cardIdPrefix()
	 * @used-by \Df\StripeClone\Payer::tokenIsNew()
	 */
	protected function cardIdPrefix():string {return 'card_';}
}
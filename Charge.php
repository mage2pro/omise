<?php
namespace Dfe\Omise;
// 2016-11-13
/** @method Settings ss() */
final class Charge extends \Df\StripeClone\Charge {
	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Charge::cardIdPrefix()
	 * @used-by \Df\StripeClone\Charge::usePreviousCard()
	 * @return mixed
	 */
	protected function cardIdPrefix() {return 'card';}

	/**
	 * 2016-11-15
	 * @override
	 * @see \Df\StripeClone\Charge::customerParams()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @return array(string => mixed)
	 */
	protected function customerParams() {return [
		// 2016-11-15
		// «(optional) A card token in case you want to add a card to the customer.»
		// https://www.omise.co/customers-api#customers-create
		'card' => $this->token()
		,'description' => $this->customerName()
		,'email' => $this->customerEmail()
	];}

	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Charge::keyCardId()
	 * @used-by \Df\StripeClone\Charge::_request()
	 * @return mixed
	 */
	protected function keyCardId() {return 'card';}

	/**
	 * 2016-11-13
	 * https://www.omise.co/charges-api#charges-create
	 * @override
	 * @see \Df\StripeClone\Charge::scRequest()
	 * @used-by \Df\StripeClone\Charge::_request()
	 * @return array(string => mixed)
	 */
	protected function scRequest() {/** @var Settings $s */ $s = $this->ss(); return [
		'amount' => $this->amountF()
		// 2016-11-16
		// «(optional) Whether or not you want the charge to be captured right away,
		// when not specified it is set to true.»
		// 2016-11-17
		// «If you have created a charge and passed capture=false,
		// you'll have an authorized only charge that you can capture anytime within 7 days.
		// After that, the charge will expire.»
		// https://www.omise.co/charges-api#charges-capture
		,'capture' => $this->needCapture()
		,'currency' => $this->currencyC()
		// 2016-11-16
		// «(optional) A custom description for the charge.
		// This value can be searched for in your dashboard.»
		,'description' => $this->text($s->description())
	] + (!$s->_3DS() ? [] : [
		/**
		 * 2016-12-24
		 * «(optional) The url where we will return the customer
		 * after the charge has been authorized with 3-D Secure.»
		 * https://www.omise.co/charges-api#charges-create
		 * https://www.omise.co/how-to-implement-3-D-Secure
		 * 2016-12-25
		 * Omise does not pass any parameters with this request.
		 * but it looks like we do not ever need it,
		 * because we can find out the customer's last order in his session.
		 *
		 * Note that in the Checkout.com module we use the same URL
		 * for both the 3D Secure return and for the Webhooks.
		 * https://github.com/checkout/checkout-magento2-plugin/blob/eee8ad66/Controller/Index/Index.php?ts=4#L19-L23
		 * We do it because Checkout.com does not allow to set the URL for 3D Secure return dynamically,
		 * (it needed to be set manually by the Checkout.com support),
		 * so we want to make these URLs simpler for humans.
		 *
		 * In the Omise case we set the URL for 3D Secure return dynamically (here),
		 * so we prefer to use separate URLs for the Webhooks and 3D Secure return.
		 * @see \Dfe\Omise\Controller\CustomerReturn\Index
		 */
		'return_uri' => $this->customerReturn()
	]);}
}
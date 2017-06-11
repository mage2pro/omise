<?php
namespace Dfe\Omise;
// 2016-11-13
/** @method Settings s() */
final class Charge extends \Df\StripeClone\Charge {
	/**
	 * 2017-02-11
	 * @override
	 * @see \Df\StripeClone\Charge::cardIdPrefix()
	 * @used-by \Df\StripeClone\Charge::usePreviousCard()
	 * @return string
	 */
	protected function cardIdPrefix() {return 'card';}

	/**
	 * 2016-11-13
	 * https://www.omise.co/charges-api#charges-create
	 * @override
	 * @see \Df\StripeClone\Charge::pCharge()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {return !$this->s()->_3DS() ? [] : [
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
	];}

	/**
	 * 2017-02-11
	 * 2017-02-18
	 * Ключ, значением которого является токен банковской карты.
	 * Этот ключ передаётся как параметр ДВУХ РАЗНЫХ запросов к API ПС:
	 * 1) в запросе на проведение транзакции (charge)
	 * 2) в запросе на сохранение банковской карты для будущего повторного использования
	 * У Omise название этого параметра для обоих запросов совпадает.
	 * @override
	 * @see \Df\StripeClone\Charge::k_CardId()
	 * @used-by \Df\StripeClone\Charge::newCard()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @return string
	 */
	protected function k_CardId() {return 'card';}

	/**
	 * 2017-02-18
	 * Does Omise support dynamic statement descriptors? https://mage2.pro/t/2822
	 * @override
	 * @see \Df\StripeClone\Charge::k_DSD()
	 * @used-by \Df\StripeClone\Charge::request()
	 * @return string
	 */
	protected function k_DSD() {return null;}
}
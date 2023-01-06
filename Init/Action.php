<?php
namespace Dfe\Omise\Init;
/**   
 * 2017-03-21
 * @method \Dfe\Omise\Method m() 
 * @method \Dfe\Omise\Settings s() 
 */
final class Action extends \Df\Payment\Init\Action {
	/**
	 * 2016-12-24 A result looks like «http://api.omise.co/payments/paym_test_56fuvl1ih89gj1kjzid/authorize».
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 */
	protected function redirectUrl():string {return !$this->s()->_3DS() ? '' :
		/**
		 * 2016-12-24
		 * «Url for charge authorization using 3-D Secure. Only if return_uri was set.»
		 * https://www.omise.co/charges-api
		 * 2017-11-07
		 * Note 1. @uses \Df\StripeClone\Method::chargeNew() caches its result.
		 * Note 2.
		 * Previously, I called @see \Df\StripeClone\Method::chargeNew() here,
		 * but now I call @uses \Df\StripeClone\Method::charge(),
		 * because it additionally adds some tags for Sentry.
		 */
		$this->m()->charge($this->preconfiguredToCapture())['authorize_uri']
	;}	
}
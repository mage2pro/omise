<?php
namespace Dfe\Omise\Init;
/**   
 * 2017-03-21
 * @method \Dfe\Omise\Method m() 
 * @method \Dfe\Omise\Settings s() 
 */
final class Action extends \Df\Payment\Init\Action {
	/**
	 * 2016-12-24
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 * @return string|null
	 * An example of result: http://api.omise.co/payments/paym_test_56fuvl1ih89gj1kjzid/authorize
	 */
	protected function redirectUrl() {return !$this->s()->_3DS() ? null :
		// 2016-12-24
		// «Url for charge authorization using 3-D Secure. Only if return_uri was set.»
		// https://www.omise.co/charges-api
		$this->m()->chargeNew($this->amount(), $this->preconfiguredToCapture())['authorize_uri']
	;}	
}
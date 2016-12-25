<?php
// 2016-11-10
namespace Dfe\Omise;
/** @method static Settings s() */
final class Settings extends \Df\StripeClone\Settings {
	/**
	 * 2016-12-24
	 * «Whether the 3D Secure Validation is enabled for your Omise account»
	 * @used-by \Dfe\Omise\Charge::_request()
	 * @return bool
	 */
	public function _3DS() {return $this->testableB();}

	/**
	 * 2016-11-13
	 * https://github.com/omise/omise-php#the-code
	 * @return void
	 */
	public function init() {dfc($this, function() {
		/** @used-by \OmiseObject::__construct() */
		define('OMISE_PUBLIC_KEY', $this->publicKey());
		define('OMISE_SECRET_KEY', $this->testableP('secretKey'));
	});}
}
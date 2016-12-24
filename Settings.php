<?php
// 2016-11-10
namespace Dfe\Omise;
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings\StripeClone {
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
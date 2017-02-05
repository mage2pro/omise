<?php
// 2016-11-10
namespace Dfe\Omise;
use Dfe\Omise\Api\Customer as AC;
/** @method Settings s() */
final class ConfigProvider extends \Df\StripeClone\ConfigProvider {
	/**
	 * 2016-11-15
	 * @override
	 * @see \Df\Payment\ConfigProvider\BankCard::savedCards()
	 * @used-by \Df\Payment\ConfigProvider\BankCard::config()
	 * @return array(string => string)
	 */
	protected function savedCards() {
		/** @var array(mixed => mixed) $result */
		$result = [];
		/** @var string|null $apiId */
		$apiId = ApiCustomerId::get();
		if ($apiId) {
			$this->s()->init();
			/** @var \OmiseCustomer $c */
			$c = \OmiseCustomer::retrieve($apiId);
			if ($c->isDestroyed()) {
				ApiCustomerId::save(null);
			}
			else {
				$result = AC::_cards($c);
			}
		}
		return $result;
	}
}
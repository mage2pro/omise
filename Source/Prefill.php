<?php
namespace Dfe\Omise\Source;
// 2016-11-10
class Prefill extends \Df\Config\SourceT {
	/**
	 * 2016-11-10
	 * https://www.omise.co/api-testing#test-cards
	 * https://mage2.pro/t/2262
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {return [
		0 => 'No'
		,'4242424242424242' => 'Visa (4242424242424242)'
		,'4111111111111111' => 'Visa (4111111111111111)'
		,'5555555555554444' => 'MasterCard (5555555555554444)'
		,'5454545454545454' => 'MasterCard (5454545454545454)'
		,'3530111333300000' => 'JCB (3530111333300000)'
		,'3566111111111113' => 'JCB (3566111111111113)'
	];}
}
<?php
// 2016-11-16
namespace Dfe\Omise;
final class ResponseRecord extends \Df\StripeClone\ResponseRecord {
	/**
	 * 2017-01-13
	 * @override
	 * @see \Df\StripeClone\ResponseRecord::keyCard()
	 * @used-by \Df\StripeClone\ResponseRecord::_card()
	 * @return string
	 */
	 protected function keyCard() {return 'card';}
}
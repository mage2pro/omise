<?php
// 2017-01-11
namespace Dfe\Omise;
class WebhookF extends \Df\StripeClone\WebhookF {
	/**             
	 * 2017-01-11
	 * @override
	 * @see \Df\StripeClone\WebhookF::typeKey()
	 * @used-by \Df\StripeClone\WebhookF::_class()
	 * @return string
	 */
	final protected function typeKey() {return 'type';}
}



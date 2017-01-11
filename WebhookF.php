<?php
// 2017-01-11
namespace Dfe\Omise;
class WebhookF extends \Df\StripeClone\WebhookF {
	/**             
	 * 2017-01-11
	 * Как и у Stripe, это ключ  отсутствует в логе события на странице события в интерфейсе Omise.
	 * Например: https://dashboard.omise.co/test/events/evnt_test_56mt7g5ehb93e8lzg7g
	 * Но реально этот ключ присутствует в запросе.
	 * @override
	 * @see \Df\StripeClone\WebhookF::typeKey()
	 * @used-by \Df\StripeClone\WebhookF::_class()
	 * @return string
	 */
	final protected function typeKey() {return 'key';}
}



<?php
namespace Dfe\Omise\W;
# 2017-03-10
final class Reader extends \Df\Payment\W\Reader\Json {
	/**             
	 * 2017-01-11
	 * Как и у Stripe, это ключ  отсутствует в логе события на странице события в интерфейсе Omise.
	 * Например: https://dashboard.omise.co/test/events/evnt_test_56mt7g5ehb93e8lzg7g
	 * Но реально этот ключ присутствует в запросе.
	 * 2017-02-14
	 * [Omise] An example of the «charge.capture» event (being sent to a webhook)
	 * https://mage2.pro/t/2746
	 * @override
	 * @see \Df\Payment\W\Reader::kt()
	 * @used-by \Df\Payment\W\Reader::tRaw()
	 * @return string
	 */
	protected function kt() {return 'key';}
}
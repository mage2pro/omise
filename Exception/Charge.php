<?php
namespace Dfe\Omise\Exception;
use Dfe\Omise\SDK\O as AO;
# 2017-01-09
# В отличие от Stripe PHP API, Omise PHP API не возбуждает исключительную ситуацию в случае неуспешности транзакции,
# а вместо этого возвращает статус транзакции и диагностическую информацию при сбое.
final class Charge extends \Dfe\Omise\Exception {
	/**
	 * 2017-01-09
	 * @param array(string => mixed) $request
	 */
	function __construct(\OmiseCharge $c, array $request) {
		$this->_c = $c;
		$this->_request = $request;
		parent::__construct();
	}

	/**
	 * 2016-07-31
	 * @used-by \Df\Qa\Failure\Exception::main()
	 */
	function isMessageHtml():bool {return true;}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Core\Exception::message()
	 * @used-by df_xts()
	 * @used-by \Df\Core\Exception::throw_()
	 */
	function message():string {return df_api_rr_failed($this, AO::_values($this->_c), $this->_request);}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Core\Exception::messageC()
	 * @used-by \Df\Payment\PlaceOrderInternal::message()
	 */
	function messageC():string {return dfp_error_message($this->_c['failure_message']);}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Core\Exception::messageD()
	 * @used-by df_xtsd()
	 * @used-by \Df\Core\Exception::messageL()
	 * @used-by \Df\Core\Exception::messageSentry()
	 * @used-by \Df\Payment\PlaceOrderInternal::message()
	 * @used-by \Df\Qa\Failure\Exception::main()
	 * @used-by \Df\Sentry\Client::captureException()
	 */
	function messageD():string {return $this->_c['failure_message'];}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Core\Exception::sentryContext()
	 * @used-by df_sentry()
	 * @return array(string => mixed)
	 */
	function sentryContext():array {return [
		'extra' => ['request' => $this->_request, 'response' => AO::_values($this->_c)]
		,'tags' => ['Omise' => df_package_version($this)]
	];}

	/**
	 * 2017-01-09
	 * @used-by \Dfe\Omise\Exception\Charge::__construct()
	 * @used-by \Dfe\Omise\Exception\Charge::message()
	 * @var \OmiseCharge
	 */
	private $_c;

	/**
	 * 2017-01-09
	 * @used-by \Dfe\Omise\Exception\Charge::__construct()
	 * @used-by \Dfe\Omise\Exception\Charge::message()
	 * @var array(string => mixed)
	 */
	private $_request;

	/**
	 * 2017-01-09
	 * @param array(string => mixed) $request
	 * @throws self
	 */
	static function assert(\OmiseCharge $c, array $request):\OmiseCharge {
		$status = $c['status']; /** @var string $status */
		# 2017-01-09
		# «Value can one be one of failed, pending, reversed or successful»: https://www.omise.co/charges-api
		# При этом всякие пояснения для состояний «pending» и «reversed» отсутствуют.
		# Задал об этом вопрос техподдержке: https://mail.google.com/mail/u/0/#inbox/15984f6a93536411
		# 2017-01-12 Получил ответ от техподдержки: https://mage2.pro/tags/omise-charge-status
		if ('failed' === $status) {
			throw new self($c, $request);
		}
		return $c;
	}
}
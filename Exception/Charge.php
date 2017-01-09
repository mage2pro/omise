<?php
/**
 * 2017-01-09
 * В отличие от Stripe PHP API, Omise PHP API не возбуждает исключительную ситуацию
 * в случае неуспешности транзакции, а вместо этого возвращает статус транзакции
 * и диагностическую информацию при сбое.
 */
namespace Dfe\Omise\Exception;
use Dfe\Omise\Api\O as AO;
class Charge extends \Dfe\Omise\Exception {
	/**
	 * 2017-01-09
	 * @param \OmiseCharge $c
	 * @param array(string => mixed) $request
	 */
	final public function __construct(\OmiseCharge $c, array $request) {
		$this->_c = $c;
		$this->_request = $request;
		parent::__construct();
	}

	/**
	 * 2016-07-31
	 * @used-by \Df\Qa\Message\Failure\Exception::main()
	 * @return bool
	 */
	final public function isMessageHtml() {return true;}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Payment\Exception::message()
	 * @return string
	 */
	final public function message() {return df_cc_n(
		'The Omise charge is failed.'
		,"Response:", df_json_encode_pretty(AO::_values($this->_c))
		,'Request:', df_json_encode_pretty($this->_request)
	);}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Payment\Exception::messageC()
	 * @return string
	 */
	final public function messageC() {return dfp_error_message($this->_c['failure_message']);}

	/**
	 * 2017-01-09
	 * Сообщение для Sentry.
	 * @override
	 * @see \Df\Core\Exception::messageSentry()
	 * @used-by \Df\Sentry\Client::captureException()
	 * @return string
	 */
	final public function messageSentry() {return $this->_c['failure_message'];}

	/**
	 * 2017-01-09
	 * @override
	 * @see \Df\Core\Exception::sentryContext()
	 * @used-by df_sentry()
	 * @return array(string => mixed)
	 */
	final public function sentryContext() {return [
		'extra' => ['request' => $this->_request, 'response' => AO::_values($this->_c)]
		,'tags' => ['Omise' => df_package_version('mage2pro/omise')]
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
	 * @param \OmiseCharge $c
	 * @param array(string => mixed) $request
	 * @return \OmiseCharge
	 * @throws self
	 */
	public static function assert(\OmiseCharge $c, array $request) {
		/** @var string $status */
		$status = $c['status'];
		/**
		 * 2017-01-09
		 * «Value can one be one of failed, pending, reversed or successful.»
		 * https://www.omise.co/charges-api
		 * При этом всякие пояснения для состояний «pending» и «reversed» отсутствуют.
		 * Задал об этом вопрос техподдержке:
		 * https://mail.google.com/mail/u/0/#inbox/15984f6a93536411
		 */
		if ('failed' === $status) {
			throw new self($c, $request);
		}
		return $c;
	}
}
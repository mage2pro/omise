<?php
// 2016-11-10
namespace Dfe\Omise;
use Df\Core\Exception as DFE;
use Dfe\Omise\Api\O as AO;
use Dfe\Omise\Settings as S;
use Df\Payment\Source\ACR;
use Magento\Framework\Exception\LocalizedException as LE;
use Magento\Payment\Model\Info as I;
use Magento\Payment\Model\InfoInterface as II;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/** @method Settings s() */
class Method extends \Df\Payment\Method {
	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::acceptPayment()
	 * @param II|I|OP $payment
	 * @return bool
	 */
	public function acceptPayment(II $payment) {
		// 2016-03-15
		// Напрашивающееся $this->charge($payment) не совсем верно:
		// тогда не будет создан invoice.
		$payment->capture();
		return true;
	}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::canCapture()
	 * @return bool
	 */
	public function canCapture() {return true;}

	/**
	 * 2016-11-18
	 * @override
	 * A partial capture is not supported by Omise: https://www.omise.co/charges-api#charges-capture
	 * Interestinly, a partial refund is suported: https://www.omise.co/refunds-api#refunds-create
	 * @see \Df\Payment\Method::canCapturePartial()
	 * @return bool
	 */
	public function canCapturePartial() {return false;}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::canRefund()
	 * @return bool
	 */
	public function canRefund() {return true;}

	/**
	 * 2016-11-13
	 * https://www.omise.co/refunds-api#refunds-create
	 * @override
	 * @see \Df\Payment\Method::canRefundPartialPerInvoice()
	 * @return bool
	 */
	public function canRefundPartialPerInvoice() {return true;}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::canReviewPayment()
	 * @return bool
	 */
	public function canReviewPayment() {return true;}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::canVoid()
	 * @return bool
	 */
	public function canVoid() {return true;}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::denyPayment()
	 * @param II|I|OP  $payment
	 * @return bool
	 */
	public function denyPayment(II $payment) {return true;}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::initialize()
	 * @param string $paymentAction
	 * @param object $stateObject
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Model/Order/Payment.php#L336-L346
	 * @see \Magento\Sales\Model\Order::isPaymentReview()
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Model/Order.php#L821-L832
	 * @return void
	 */
	public function initialize($paymentAction, $stateObject) {
		$stateObject['state'] = O::STATE_PAYMENT_REVIEW;
	}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::isInitializeNeeded()
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Model/Order/Payment.php#L2336-L346
	 * @return bool
	 */
	public function isInitializeNeeded() {return ACR::REVIEW === $this->getConfigPaymentAction();}

	/**
	 * 2016-11-18
	 * @override
	 * @see \Df\Payment\Method::_refund()
	 * @used-by \Df\Payment\Method::refund()
	 * @param float|null $amount
	 * @return void
	 */
	protected function _refund($amount) {$this->api(function() use($amount) {
		/**
		 * 2016-03-17
		 * Метод @uses \Magento\Sales\Model\Order\Payment::getAuthorizationTransaction()
		 * необязательно возвращает транзакцию типа «авторизация»:
		 * в первую очередь он стремится вернуть родительскую транзакцию:
		 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Model/Order/Payment/Transaction/Manager.php#L31-L47
		 * Это как раз то, что нам нужно, ведь наш модуль может быть настроен сразу на capture,
		 * без предварительной транзакции типа «авторизация».
		 */
		/** @var T|false $tFirst */
		$tFirst = $this->ii()->getAuthorizationTransaction();
		if ($tFirst) {
			/** @var string $firstId */
			$firstId = $this->transParentId($tFirst->getTxnId());
			/** @var \OmiseCharge $charge */
			$charge = \OmiseCharge::retrieve($firstId);
			// 2016-03-24
			// Credit Memo и Invoice отсутствуют в сценарии Authorize / Capture
			// и присутствуют в сценарии Capture / Refund.
			if ($this->ii()->getCreditmemo()) {
				/** @var \OmiseRefund $refund */
				$refund = $charge->refunds()->create(['amount' => $this->amountFormat($amount)]);
				$this->transInfo($refund);
				$this->ii()->setTransactionId($refund['id']);
			}
			else {
				// 2016-11-18
				// Reverse an uncaptured charge: https://www.omise.co/charges-api#charges-reverse
				$charge->reverse();
				$this->ii()->setTransactionId("{$firstId}-void");
			}
		}
	});}

	/**
	 * 2016-11-18
	 * Reverse an uncaptured charge: https://www.omise.co/charges-api#charges-reverse
	 * @override
	 * @see \Df\Payment\Method::_void()
	 * @return void
	 */
	protected function _void() {$this->_refund(null);}

	/**
	 * 2016-11-15
	 * https://www.omise.co/currency-and-amount
	 * @override
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by isAvailable()
	 * @return array(string => array(int|float))
	 */
	protected function amountLimits() {return ['THB' => [20, 1000000], 'JPY' => [100, 999999]];}

	/**
	 * 2016-11-10
	 * @override
	 * @see \Df\Payment\Method::charge()
	 * @param float $amount
	 * @param bool|null $capture [optional]
	 * @return void
	 */
	protected function charge($amount, $capture = true) {$this->api(function() use($amount, $capture) {
		/** @var T|false|null $auth */
		$auth = !$capture ? null : $this->ii()->getAuthorizationTransaction();
		if ($auth) {
			// 2016-11-17
			// https://www.omise.co/charges-api#charges-capture
			/** @var \OmiseCharge $charge */
			$charge = \OmiseCharge::retrieve($auth->getTxnId());
			$charge->capture();
		}
		else {
			/** @var array(string => mixed) $params */
			$params = Charge::request($this, $this->iia(self::$TOKEN), $amount, $capture);
			/** @var \OmiseCharge $charge */
			$charge = $this->api($params, function() use($params) {
				return \OmiseCharge::create($params);
			});
			/**
			 * 2016-11-16
				{
					"object": "card",
					"id": "card_test_560jgvu90914d44h1vx",
					"livemode": false,
					"location": "/customers/cust_test_560jgw6s43s7i4ydd8r/cards/card_test_560jgvu90914d44h1vx",
					"country": "us",
					"city": null,
					"postal_code": null,
					"financing": "",
					"bank": "",
					"last_digits": "4444",
					"brand": "MasterCard",
					"expiration_month": 7,
					"expiration_year": 2019,
					"fingerprint": "/uCzRPQQRUDr8JvGUjKf7Xn10VRJeQ7oBZ1Zt7gLvWs=",
					"name": "DMITRY FEDYUK",
					"security_code_check": true,
					"created": "2016-11-15T22:00:49Z"
				}
			 * @var array(string => string|bool|int|null) $card
			 */
			$card = $charge['card'];
			$this->transInfo($charge, $params);
			$this->ii()->setCcLast4($card['last_digits']);
			$this->ii()->setCcType($card['brand']);
			/**
			 * 2016-03-15
			 * Иначе операция «void» (отмена авторизации платежа) будет недоступна:
			 * «How is a payment authorization voiding implemented?»
			 * https://mage2.pro/t/938
			 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Model/Order/Payment.php#L540-L555
			 * @used-by \Magento\Sales\Model\Order\Payment::canVoid()
			 */
			$this->ii()->setTransactionId($charge['id']);
			/**
			 * 2016-03-15
			 * Аналогично, иначе операция «void» (отмена авторизации платежа) будет недоступна:
			 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Model/Order/Payment.php#L540-L555
			 * @used-by \Magento\Sales\Model\Order\Payment::canVoid()
			 * Транзакция ситается завершённой, если явно не указать «false».
			 */
			$this->ii()->setIsTransactionClosed($capture);
		}
	});}

	/**
	 * 2016-11-17
	 * @override
	 * @see \Df\Payment\Method::transUrl()
	 * @used-by \Df\Payment\Method::formatTransactionId()
	 * @param T $t
	 * @return string
	 */
	protected function transUrl(T $t) {return df_cc_path('https://dashboard.omise.co',
		df_trans_is_test($t, 'test', 'live'), 'charges', $this->transParentId($t->getTxnId())
	);}

	/**
	 * 2016-11-10
	 * @override
	 * @see \Df\Payment\Method::iiaKeys()
	 * @used-by \Df\Payment\Method::assignData()
	 * @return string[]
	 */
	protected function iiaKeys() {return [self::$TOKEN];}

	/**
	 * 2016-11-13
	 * Чтобы система показала наше сообщение вместо общей фразы типа
	 * «We can't void the payment right now» надо вернуть объект именно класса
	 * @uses \Magento\Framework\Exception\LocalizedException
	 * https://mage2.pro/t/945
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Controller/Adminhtml/Order/VoidPayment.php#L20-L30
	 * @param array(callable|array(string => mixed)) ... $args
	 * @return mixed
	 * @throws Exception|LE
	 */
	private function api(...$args) {
		/** @var callable $function */
		/** @var array(string => mixed) $request */
		$args += [1 => []];
		list($function, $request) = is_callable($args[0]) ? $args : array_reverse($args);
		try {S::s()->init(); return $function();}
		catch (DFE $e) {throw $e;}
		//catch (EStripe $e) {throw new Exception($e, $request);}
		catch (\Exception $e) {throw df_le($e);}
	}

	/**
	 * 2016-11-16
	 * @used-by \Dfe\Omise\Method::_refund()
	 * @used-by \Dfe\Omise\Method::charge()
	 * @param \OmiseApiResource $response
	 * @param array(string => mixed) $request [optional]
	 * @return void
	 */
	private function transInfo(\OmiseApiResource $response, array $request = []) {
		$this->iiaSetTRR(array_map('df_json_encode_pretty', [$request, AO::_values($response)]));
	}

	/**
	 * 2016-11-17
	 * @used-by \Dfe\Omise\Method::_refund()
	 * @used-by \Dfe\Omise\Method::transUrl()
	 * @param string $childId
	 * @return string
	 */
	private function transParentId($childId) {return df_first(explode('-', $childId));}

	/**
	 * 2016-11-10
	 * @var string
	 */
	private static $TOKEN = 'token';
}
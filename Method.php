<?php
// 2016-11-10
namespace Dfe\Omise;
use Df\Core\Exception as DFE;
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
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::canCapturePartial()
	 * @return bool
	 */
	public function canCapturePartial() {return true;}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::canRefund()
	 * @return bool
	 */
	public function canRefund() {return true;}

	/**
	 * 2016-11-13
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
		}
		else {

		}
	});}

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
	 * 2016-11-10
	 * @var string
	 */
	private static $TOKEN = 'token';
}
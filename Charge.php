<?php
// 2016-11-13
namespace Dfe\Omise;
use Df\Core\Exception as DFE;
use Df\Payment\Metadata;
use Dfe\Omise\Settings as S;
use Magento\Sales\Model\Order\Address;
use Magento\Sales\Model\Order\Payment as OP;
class Charge extends \Df\Payment\Charge\WithToken {
	/**
	 * 2016-11-13
	 * https://www.omise.co/charges-api#charges-create
	 * @return array(string => mixed)
	 */
	private function _request() {/** @var Settings $s */ $s = S::s(); return [
	];}

	/** @return bool */
	private function needCapture() {return $this[self::$P__NEED_CAPTURE];}

	/**
	 * 2016-11-13
	 * @override
	 * @return void
	 */
	protected function _construct() {
		parent::_construct();
		$this->_prop(self::$P__NEED_CAPTURE, DF_V_BOOL, false);
	}

	/** @var string */
	private static $P__NEED_CAPTURE = 'need_capture';

	/**
	 * 2016-11-13
	 * @used-by \Dfe\Stripe\Method::charge()
	 * @param Method $method
	 * @param string $token
	 * @param float|null $amount [optional]
	 * @param bool $capture [optional]
	 * @return array(string => mixed)
	 */
	public static function request(Method $method, $token, $amount = null, $capture = true) {return
		(new self([
			self::$P__AMOUNT => $amount
			, self::$P__NEED_CAPTURE => $capture
			, self::$P__METHOD => $method
			, self::$P__TOKEN => $token
		]))->_request()
	;}
}
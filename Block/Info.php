<?php
// 2016-11-16
namespace Dfe\Omise\Block;
use Dfe\Omise\Method;
use Dfe\Omise\Response;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/** @method Method m() */
class Info extends \Df\Payment\Block\Info {
	/**
	 * 2016-11-16
	 * @override
	 * @see \Magento\Payment\Block\ConfigurableInfo::_prepareSpecificInformation()
	 * @used-by \Magento\Payment\Block\Info::getSpecificInformation()
	 * @param DataObject|null $transport
	 * @return DataObject
	 */
	protected function _prepareSpecificInformation($transport = null) {
		/** @var DataObject $result */
		$result = parent::_prepareSpecificInformation($transport);
		if ($this->isBackend()) {
			$result['Omise ID'] = $this->m()->formatTransactionId($this->res()->id());
		}
		$result[$this->isBackend() ? 'Card Number' : 'Number'] = $this->res()->card();
		if ($this->isBackend()) {
			$result->addData([
				'Card Expires' => $this->res()->expires()
				,'Card Country' => $this->res()->country()
			]);
		}
		$this->markTestMode($result);
		return $result;
	}

	/**
	 * 2016-11-16
	 * @return Response
	 */
	private function res() {return dfc($this, function() {return Response::i($this->transF());});}
}
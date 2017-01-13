<?php
// 2016-11-16
namespace Dfe\Omise\Block;
use Dfe\Omise\Card;
use Dfe\Omise\ResponseRecord;
/** @method \Dfe\Omise\Method m() */
class Info extends \Df\Payment\Block\Info {
	/**
	 * 2016-11-17
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	protected function prepare() {
		/** @var Card $c */
		$c = ResponseRecord::i($this->transF())->card();
		$this->siB('Omise ID', $this->m()->formatTransactionId($this->transF()));
		$this->si($this->isBackend() ? 'Card Number' : 'Number', (string)$c);
		$this->siB(['Card Expires' => $c->expires(), 'Card Country' => $c->country()]);
	}
}
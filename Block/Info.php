<?php
// 2016-11-16
namespace Dfe\Omise\Block;
use Dfe\Omise\Message;
/** @method \Dfe\Omise\Method m() */
class Info extends \Df\Payment\Block\Info {
	/**
	 * 2016-11-17
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	protected function prepare() {
		/** @var Message $r */
		$r = Message::i($this->transF());
		$this->siB('Omise ID', $this->m()->formatTransactionId($this->transF()));
		$this->si($this->isBackend() ? 'Card Number' : 'Number', $r->card());
		$this->siB(['Card Expires' => $r->expires(), 'Card Country' => $r->country()]);
	}
}
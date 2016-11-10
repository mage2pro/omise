<?php
// 2016-11-10
namespace Dfe\Omise\Controller\Index;
use Df\Framework\Controller\Result\Json;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-11-10
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Json
	 */
	public function execute() {return Json::i('OK');}
}
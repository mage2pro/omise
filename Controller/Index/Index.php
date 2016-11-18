<?php
// 2016-11-10
namespace Dfe\Omise\Controller\Index;
use Df\Framework\Controller\Result\Json;
//use Dfe\Omise\Handler;
use Dfe\Omise\Settings as S;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-11-10
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Json
	 */
	public function execute() {return df_leh(function(){
		S::s()->init();
		dfp_report($this, df_ruri(), 'request-uri');
		dfp_report($this, df_request(), 'request-params');
		return Json::i('OK');
		//return Json::i(Handler::p(df_json_decode(@file_get_contents($this->file()))));
	});}

	/**
	 * 2016-11-17
	 * @return string
	 */
	private function file() {return
		df_my_local() ? df_test_file($this, 'charge.capture.json') : 'php://input'
	;}
}
<?php
// 2016-11-10
namespace Dfe\Omise\Controller\Index;
use Df\Framework\Controller\Result\Json;
use Dfe\Omise\Settings as S;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-11-10
	 * We get here on an Omise's webhook notification.
	 * «WebHooks are simple HTTP POST requests that will be triggered from Omise servers
	 * whenever an action has been performed either through the API, or through the dashboard.
	 * Those requests will include an event object containing a data payload relative to the action.»
	 * https://www.omise.co/api-webhooks
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Json
	 */
	public function execute() {return df_leh(function(){
		S::s()->init();
		/** @var array(string => mixed) $request */
		$request = df_json_decode(@file_get_contents($this->file()));
		// 2016-12-15
		// A string like «Omise/2015-11-17».
		df_sentry_m()->user_context(['id' => df_request_ua()]);
		dfp_report($this, $request, 'webhook: ' . dfa($request, 'key'));
		return '';//Json::i(Handler::p($request));
	});}

	/**
	 * 2016-11-17
	 * @return string
	 */
	private function file() {return
		df_my_local() ? df_test_file($this, 'charge.capture.json') : 'php://input'
	;}
}
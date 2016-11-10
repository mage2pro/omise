<?php
// 2016-11-10
namespace Dfe\Omise;
/** @method Settings s() */
class ConfigProvider extends \Df\Payment\ConfigProvider\BankCard {
	/**
	 * 2016-11-10
	 * @override
	 * @see \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function config() {return [
	] + parent::config();}
}
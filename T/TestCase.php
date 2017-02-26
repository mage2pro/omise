<?php
namespace Dfe\Omise\T;
// 2016-11-13
/** @method \Dfe\Omise\Settings s() */
abstract class TestCase extends \Df\Core\TestCase {
	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Core\TestCase::setUp()
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();
		$this->s()->init();
		/**
		 * 2016-11-13
		 * Метод @see \OmiseApiResource::execute() норовит по $_SERVER['SCRIPT_NAME'] решить,
		 * надо ли ему реально обращаться к серверу или же мухлевать:
		 * https://github.com/omise/omise-php/blob/v2.5.0/lib/omise/res/OmiseApiResource.php#L113-L118
		 */
		$_SERVER['SCRIPT_NAME'] = 'fake';
	}
}
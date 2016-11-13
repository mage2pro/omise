<?php
// 2016-11-13
namespace Dfe\Omise\T;
use Dfe\Omise\Settings as S;
abstract class TestCase extends \Df\Core\TestCase {
	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Core\TestCase::setUp()
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();
		S::s()->init();
	}
}
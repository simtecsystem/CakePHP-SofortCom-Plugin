<?php
/**
 * All  plugin tests
 */
class AllSofortComTest extends CakeTestCase {

	/**
	 * Suite define the tests for this plugin
	 *
	 * @return CakeTestSuite
	 */
	public static function suite() {
		$suite = new CakeTestSuite('All test');

		$path = CakePlugin::path('SofortCom') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}

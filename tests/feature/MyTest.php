<?php

namespace tests\feature;

use tests\BaseTest;

class MyTest extends BaseTest
{
	/**
	 * @test
	 */
	public function demo()
	{
		$data = ["ok feature"];

		print_r($data);

		$this->assertTrue(true, 'already.');
	}
}
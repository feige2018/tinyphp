<?php

namespace tests\unit;

use tests\BaseTest;

class MyTest extends BaseTest
{
	/**
	 * @test
	 */
	protected function setUp(): void
	{

	}

	/**
	 * @test
	 */
	public function demo()
	{
		$data = ["ok unit"];

//		$data = get_included_files();

		print_r($data);

		$this->assertTrue(true);
	}

	/**
	 * @test
	 */
	public function temp()
	{
		$data = [];

		print_r($data);

		$this->assertTrue(true);
	}
}

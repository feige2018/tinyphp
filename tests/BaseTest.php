<?php

namespace tests;

use PHPUnit\Framework\TestCase;

(new \tiny\TinyPHP)->run();

/**
 * Class BaseTest
 * 测试类命名：类名 + Test
 * 测试方法名：test + 方法名
 * 或者在注解中加上 @test ，这样 方法名中就不用加 test 前缀.
 */

class BaseTest extends TestCase
{
	/**
	 * @test
	 */
	public function demo()
	{
		$this->assertTrue(true, 'already.');
	}

	/**
	 * @test
	 */
	protected function setUp(): void
	{

	}

	protected function tearDown(): void
	{

	}
}

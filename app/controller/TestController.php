<?php

namespace app\controller;

class TestController extends BaseController
{
	public function test($name = "test")
	{
		$data = [
			"name" => $name,
		];

		return json($data);
	}

	public function testa($name = "testa", $age = 0)
	{
		$data = [
			"age" => $age,
			"name" => $name,
		];

		return json($data);
	}
}

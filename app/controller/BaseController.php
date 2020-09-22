<?php

namespace app\controller;

class BaseController
{
	public static function instance()
	{
		return new static();
	}

	public function index()
	{
		return json();
	}

	public function miss()
	{
		return json(null, 404, "not found");
	}
}

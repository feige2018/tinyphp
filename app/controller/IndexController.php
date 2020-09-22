<?php

namespace app\controller;

class IndexController extends BaseController
{
	/**
	 * 首页
	 */
	public function index()
	{
		$data = [
			"title" => config("app.name"),
		];

		return json($data);
	}

	public function news()
	{
		$data = [
			"title" => "news",
		];

		return json($data);
	}
}

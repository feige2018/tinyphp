<?php

function getDb($table = "")
{
	return \tiny\Db::instance($table);
}

function http_send($url, $data = [], $method = "get", $options = [], $timeout = 30)
{
	$return = ["code" => 0, "message" => "", "data" => "", "headers" => "", "response" => "", "error" => ""];

	if (! filter_var($url, FILTER_VALIDATE_URL)) {
		$return["message"] = "无效的 url: '$url'";
		return $return;
	}
	$parseUrl = parse_url($url);
	if(empty($parseUrl['scheme']) || !in_array($parseUrl['scheme'], ["http","https","tcp","ftp"]) || empty($parseUrl['host'])) {
		$return["message"] = "Invalid url: '$url'";
		return $return;
	}
	$port = !empty($parseUrl['port']) ? ':'.$parseUrl['port'] : '';
	$origin = $parseUrl['scheme'] . '://' . $parseUrl['host'] . $port;
	$queryStr = isset($parseUrl['query']) ? $parseUrl['query'] : "";

	if(empty($data)) {
		$data = [];
	}
	elseif (is_string($data)) {
		parse_str(trim($data, '?&'), $data);
	}

	$method = strtoupper($method);

	$defaultOptions = [
		'timeout' => $timeout,
		'connect_timeout' => $timeout,
		'http_errors' => true, // 设置成 false 来禁用 HTTP 协议抛出的异常(如 4xx 和 5xx 响应)，默认情况下 HTPP 协议出错时会抛出异常
		'verify' => false, // 设置成 false 不验证证书，可以设置成证书路径：'/path/to/cert.pem'
		'allow_redirects' => true, // 默认为 true，启用最大数量为 5 的重定向；设置成 false 来禁用重定向
		'force_ip_resolve' => 'v4', // 使用 IPv4
		'headers' => [
		//	'Origin' => $origin,
		//	'Host' => $parseUrl['host'],
		//	'Referer' => $origin,
			'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36',
		],
	];

	if ($options && is_array($options)) {
		$options = array_replace_recursive($defaultOptions, $options);
	} else {
		$options = $defaultOptions;
	}
	unset($defaultOptions);

	if (!empty($options['auth'])) {
		if (is_string($options['auth'])) {
			$options['auth'] = explode(':', $options['auth']);
		}
	}

	$params = [];

	if ($method == "GET") {
		$queryArr = [];
		if ($queryStr) {
			parse_str($queryStr, $queryArr);
		}
		$params['query'] = array_merge($queryArr, $data);
	}
	elseif ($method == "POST") {
		$params['form_params'] = $data;
	}
	elseif ($method == "JSON") {
		$params['json'] = $data;
		$method = 'POST';
	}
	elseif ($method == "FILE") {
		/**
		 * $data 必须是二维数组，格式为：
			[
			    ["name" => "field_name", "contents" => "field_value"],
			    ["name" => "field_name", "contents" => fopen("/path/to/filename", "rb"), "filename" => "filename"]
			]
		 */
		if (!isset($data[0]['name'])) {
			return ["code" => 0, "data" => ""];
		}
		$params['multipart'] = $data;
		$method = 'POST';
	}
	else {
		$return["message"] = "不支持 $method";
		return $return;
	}

	try {
		$client = new \GuzzleHttp\Client($options);
		$response = $client->request($method, $url, $params);
		$return["code"] = $response->getStatusCode();
		$return["data"] = (string)$response->getBody();
		$return["headers"] = $response->getHeaders();
	}
	catch (\GuzzleHttp\Exception\RequestException $re) {
		$return["code"] = $re->getCode();
		$return["message"] = "请求失败";
		$return["error"] = $re->getMessage();
		if ($re->hasResponse()) {
			$return["response"] = $re->getResponse();
		}
	}
	catch (\Throwable $e) {
		$return["code"] = $e->getCode();
		$return["message"] = "请求失败";
		$return["error"] = $e->getMessage();
	}

	return $return;
}

/**
 * 请求 API.
 * @param string $url
 * @param array $data
 * @param string $method
 * @param int $timeout
 * @param array $options
 * @return array: ["code" => 1, "message" => "", "data" => ""];
 */
function request_api($url, $data = [], $method = "post", $options = [], $timeout = 30)
{
	$allow_debug_ua = "";
	$res = http_send($url, $data, $method, $options, $timeout);

	if (!$allow_debug_ua) {
		unset($res["error"]);
	}

	if (!in_array($res["code"], [200, 201, 202, 204, 205, 206])) {
		return $res;
	}

	$json = json_decode($res["data"], true);

	if (!is_array($json)) {
		$json["code"] = 0;
		$json["message"] = "服务异常！";
		$json["response"] = $res["data"];
		return $json;
	}
	if (!isset($json["code"])) {
		$json["code"] = 0;
	}
	if (!isset($json["message"])) {
		$json["message"] = isset($json["msg"]) ? $json["msg"] : "";
	}
	if (!isset($json["data"])) {
		$json["data"] = "";
	}
	if (stristr($json["message"], "Server Error") !== false || $json["code"] == 500) {
		if ($allow_debug_ua) {
			$json["error"] = $json["message"] . (isset($json["error"]) ? ". " . $json["error"] : "");
		}
		$json["message"] = "服务异常";
	}
	if ($allow_debug_ua && isset($res["headers"])) {
		$json["headers"] = $res["headers"];
	}

	return $json;
}

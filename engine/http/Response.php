<?php

namespace engine\http;

class Response
{
	public static function answer($data = null, $status = null, $message = null, $code = 200, $contentType = 'text/plain')
	{
		if (
			(isset($status) || isset($message))
			||
			(is_array($data) || is_object($data))
		) {
			$answer['status'] = $status ?? '';
			$answer['message'] = $message ?? '';
			$answer['data'] = $data;

			$answer = json_encode($answer, JSON_UNESCAPED_UNICODE);

			$contentType = 'application/json';
		} else if (isset($data)) {
			$answer = strval($data);
		} else {
			self::empty($code, $contentType);
		}

		http_response_code($code);

		header("Content-Type: $contentType");

		exit(strval($answer));
	}

	public static function empty($code = 200, $contentType = 'text/plain')
	{
		http_response_code($code);

		header("Content-Type: $contentType");

		exit;
	}

	public static function redirect($url, $code = 0)
	{
		header("Location: $url", true, $code);

		exit;
	}
}

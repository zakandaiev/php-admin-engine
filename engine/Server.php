<?php

namespace Engine;

class Server
{
	public static function answer($data = null, $status = null, $message = null, $code = 200, $content_type = 'text/plain')
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

			$content_type = 'application/json';
		} else if (isset($data)) {
			$answer = strval($data);
		} else {
			self::answerEmpty($code, $content_type);
		}

		http_response_code($code);

		header("Content-Type: $content_type");

		exit(strval($answer));
	}

	public static function answerEmpty($code = 200, $content_type = 'text/plain')
	{
		http_response_code($code);

		header("Content-Type: $content_type");

		exit;
	}

	public static function redirect($url, $code = 301)
	{
		header("Location: $url", true, $code);

		exit;
	}
}

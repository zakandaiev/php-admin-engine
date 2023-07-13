<?php

namespace Engine;

class Server {
	public static function answer($data = null, $status = null, $message = null, $code = 200, $content_type = 'text/plain') {
		if(is_array($data)) {
			$answer['status'] = $status ?? 'success';
			$answer['message'] = $message ?? 'success';
			$answer['data'] = $data;

			$answer = json_encode($answer);

			$content_type = 'application/json';
		}
		else if(is_string($data)) {
			$answer = $data;
		}
		else {
			self::answerEmpty($code, $content_type);
		}

		http_response_code($code);

		header("Content-Type: $content_type");

		exit(strval($answer));
	}

	public static function answerEmpty($code = 200, $content_type = 'text/plain') {
		http_response_code($code);

		header("Content-Type: $content_type");

		exit;
	}

	public static function redirect($url, $code = 301) {
		header("Location: $url", true, $code);

		exit;
	}
}

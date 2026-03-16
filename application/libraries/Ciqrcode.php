<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ciqrcode
{
	public function generate($params = array())
	{
		if (!is_array($params)) {
			$params = array('data' => (string) $params);
		}

		$text = isset($params['data']) ? trim((string) $params['data']) : '';
		if ($text === '') {
			return false;
		}

		$size = isset($params['size']) ? (int) $params['size'] : 300;
		if ($size > 0 && $size <= 20) {
			$size = $size * 25;
		}
		$size = max(100, min($size, 1000));

		$margin = isset($params['margin']) ? (int) $params['margin'] : 2;
		$margin = max(0, min($margin, 10));

		$url = 'https://quickchart.io/qr?text=' . rawurlencode($text) .
			'&size=' . $size .
			'&margin=' . $margin .
			'&format=png';

		$image = $this->fetch_remote_image($url);
		if ($image === false) {
			return false;
		}

		if (!empty($params['savename'])) {
			return file_put_contents($params['savename'], $image) !== false;
		}

		return $image;
	}

	private function fetch_remote_image($url)
	{
		if (function_exists('curl_init')) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

			$response = curl_exec($ch);
			$http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($response !== false && $http_code >= 200 && $http_code < 300) {
				return $response;
			}
		}

		$context = stream_context_create(array(
			'http' => array(
				'timeout' => 15,
			),
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
			),
		));

		$response = @file_get_contents($url, false, $context);
		return $response !== false ? $response : false;
	}
}

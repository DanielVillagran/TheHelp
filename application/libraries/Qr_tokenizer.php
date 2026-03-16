<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Qr_tokenizer
{
	private $cipher = 'AES-256-CBC';

	public function encode($value, $secret)
	{
		$secret = (string) $secret;
		if ($secret === '' || $value === null || $value === '') {
			return false;
		}

		$payload = json_encode(array(
			'value' => (string) $value,
			'ts' => time()
		));

		$key = hash('sha256', $secret, true);
		$iv_length = openssl_cipher_iv_length($this->cipher);
		$iv = openssl_random_pseudo_bytes($iv_length);
		$ciphertext = openssl_encrypt($payload, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
		if ($ciphertext === false) {
			return false;
		}

		$signature = hash_hmac('sha256', $iv . $ciphertext, $key, true);
		return $this->base64url_encode($iv . $signature . $ciphertext);
	}

	public function decode($token, $secret)
	{
		$secret = (string) $secret;
		if ($secret === '' || trim((string) $token) === '') {
			return false;
		}

		$binary = $this->base64url_decode($token);
		if ($binary === false) {
			return false;
		}

		$key = hash('sha256', $secret, true);
		$iv_length = openssl_cipher_iv_length($this->cipher);
		$signature_length = 32;

		if (strlen($binary) <= ($iv_length + $signature_length)) {
			return false;
		}

		$iv = substr($binary, 0, $iv_length);
		$signature = substr($binary, $iv_length, $signature_length);
		$ciphertext = substr($binary, $iv_length + $signature_length);

		$expected_signature = hash_hmac('sha256', $iv . $ciphertext, $key, true);
		if (!hash_equals($expected_signature, $signature)) {
			return false;
		}

		$payload = openssl_decrypt($ciphertext, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
		if ($payload === false) {
			return false;
		}

		$data = json_decode($payload, true);
		if (!is_array($data) || !isset($data['value'])) {
			return false;
		}

		return $data['value'];
	}

	private function base64url_encode($data)
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	private function base64url_decode($data)
	{
		$decoded = base64_decode(strtr($data, '-_', '+/') . str_repeat('=', (4 - strlen($data) % 4) % 4), true);
		return $decoded !== false ? $decoded : false;
	}
}

<?php

/**
 * Этот алгоритм будет работать начиная с PHP 5.6 и выше. 
 * На предыдущих версиях будет выдавать ошибку из-за функции hash_equals,
 * которая осуществляет сравнение строк нечувствительное к атакам по времени.
 */

define('ENCRYPTION_KEY', 'ss8d7744e3f080b61c7c2e32');

function ss_encript( $string ) {
	$ivlen = openssl_cipher_iv_length( $cipher = "AES-128-CBC" );
	$iv = openssl_random_pseudo_bytes( $ivlen );
	$ciphertext_raw = openssl_encrypt( $string, $cipher, ENCRYPTION_KEY, $options = OPENSSL_RAW_DATA, $iv );
	$hmac = hash_hmac( 'sha256', $ciphertext_raw, ENCRYPTION_KEY, $as_binary = true );
	return base64_encode( $iv . $hmac . $ciphertext_raw );
}

function ss_decript( $string ) {
	$c = base64_decode( $string );
	$ivlen = openssl_cipher_iv_length( $cipher = "AES-128-CBC" );
	$iv = substr( $c, 0, $ivlen );
	$hmac = substr( $c, $ivlen, $sha2len = 32 );
	$ciphertext_raw = substr( $c, $ivlen + $sha2len );
	$string = openssl_decrypt( $ciphertext_raw, $cipher, ENCRYPTION_KEY, $options = OPENSSL_RAW_DATA, $iv );
	$calcmac = hash_hmac( 'sha256', $ciphertext_raw, ENCRYPTION_KEY, $as_binary = true );
	if ( hash_equals( $hmac, $calcmac ) ) {
	    return $string;
	}
}
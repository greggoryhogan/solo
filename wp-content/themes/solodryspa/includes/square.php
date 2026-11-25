<?php

function solo_sq_customer_request( $method, $path, $body ) {
	$host = defined( 'SOLO_SQUARE_APP_ID' ) && 0 === strpos( SOLO_SQUARE_APP_ID, 'sandbox-' )
		? 'https://connect.squareupsandbox.com'
		: 'https://connect.squareup.com';

	$response = wp_remote_request( $host . $path, array(
		'method'      => $method,
		'timeout'     => 5,
		'redirection' => 0,
		'headers'     => array(
			'Authorization' => 'Bearer ' . SOLO_SQUARE_APP_TOKEN,
			'Content-Type'  => 'application/json',
			'Square-Version' => '2026-11-18',
		),
		'body'        => wp_json_encode( $body ),
	) );

	if ( is_wp_error( $response ) ) {
		error_log( 'Square Customers API request failed before receiving a response.' );
		return null;
	}

	$status = wp_remote_retrieve_response_code( $response );
	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( $status < 200 || $status >= 300 || ! is_array( $data ) || ! empty( $data['errors'] ) ) {
		$code = $data['errors'][0]['code'] ?? '';
		$code = is_string( $code ) && preg_match( '/\A[A-Z0-9_]+\z/', $code ) ? $code : 'UNKNOWN';
		error_log( sprintf( 'Square Customers API %s request failed (HTTP %d, %s).', $method, $status, $code ) );
		return null;
	}

	return $data;
}

function solo_sq_upsert_customer( $email, array $address = array() ) {
	if ( ! defined( 'SOLO_SQUARE_APP_TOKEN' ) || ! SOLO_SQUARE_APP_TOKEN || ! is_string( $email ) || ! is_email( $email ) ) {
		return null;
	}

	$search = array(
		'query' => array(
			'filter' => array(
				'email_address' => array( 'exact' => $email ),
			),
		),
	);
	$customer = null;
	do {
		$result = solo_sq_customer_request( 'POST', '/v2/customers/search', $search );
		if ( null === $result ) {
			return null;
		}

		foreach ( $result['customers'] ?? array() as $match ) {
			if ( null !== $customer || empty( $match['id'] ) ) {
				error_log( 'Square customer search did not identify a single updatable profile.' );
				return null;
			}
			$customer = $match;
		}
		$search['cursor'] = $result['cursor'] ?? '';
	} while ( '' !== $search['cursor'] );

	if ( null !== $customer ) {
		$update = array( 'email_address' => $email );
		if ( $address ) {
			$update['address'] = $address;
		}
		if ( isset( $customer['version'] ) ) {
			$update['version'] = $customer['version'];
		}
		$result = solo_sq_customer_request( 'PUT', '/v2/customers/' . rawurlencode( $customer['id'] ), $update );
		if ( empty( $result['customer']['id'] ) ) {
			return null;
		}
		return true === ( $customer['preferences']['email_unsubscribed'] ?? false );
	}

	// Square search can lag behind creation. Repeated signups use the same create request.
	$email = strtolower( $email );
	$result = solo_sq_customer_request( 'POST', '/v2/customers', array(
		'email_address'   => $email,
		'idempotency_key' => substr( hash_hmac( 'sha256', 'solo-square-customer:' . $email, wp_salt() ), 0, 32 ),
	) );
	return empty( $result['customer']['id'] ) ? null : false;
}
add_action( 'solo_sq_upsert_customer', 'solo_sq_upsert_customer', 10, 2 );

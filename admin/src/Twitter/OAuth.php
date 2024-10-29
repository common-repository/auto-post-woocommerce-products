<?php
/**
 * Description: Description of file contents
 *
 * PHP version 7.2
 *
 * @category  Description
 * Created    Monday, Aug-26-2019 at 23:54:58
 * @package   Auto_Post_Woocommerce_Products
 *
 * @author    Carl Lockett III <info@cilcreations.com>
 * @copyright 2018-2019 Carl Lockett III, CIL Creations
 * @license   https://opensource.org/licenses/GPL-3.0 GNU Public License}
 *
 * @link      https://www.cilcreations.com/apwp/support
 * @since     0.0.0.0
 */

/*
The MIT License

Copyright (c) 2007 Andy Smith

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 */

/* Generic exception class
 */
class Twitter_OAuthException extends Exception {
	// pass
}

class Twitter_OAuthConsumer {
	/**
	 * @var mixed
	 */
	public $key;
	/**
	 * @var mixed
	 */
	public $secret;

	/**
	 * @param $key
	 * @param $secret
	 * @param $callback_url
	 */
	public function __construct(
		$key,
		$secret,
		$callback_url = null
	) {
		$this->key          = $key;
		$this->secret       = $secret;
		$this->callback_url = $callback_url;
	}

	public function __toString() {
		return "OAuthConsumer[key=$this->key,secret=$this->secret]";
	}
}

class Twitter_OAuthToken {
	// access tokens and request tokens
	/**
	 * @var mixed
	 */
	public $key;
	/**
	 * @var mixed
	 */
	public $secret;

	/**
	 * key = the token
	 * secret = the token secret
	 */
	public function __construct(
		$key,
		$secret
	) {
		$this->key    = $key;
		$this->secret = $secret;
	}

	/**
	 * generates the basic string serialization of a token that a server
	 * would respond to request_token and access_token calls with
	 */
	public function to_string() {
		return 'oauth_token=' .
		Twitter_OAuthUtil::urlencode_rfc3986( $this->key ) .
		'&oauth_token_secret=' .
		Twitter_OAuthUtil::urlencode_rfc3986( $this->secret );
	}

	/**
	 * @return mixed
	 */
	public function __toString() {
		return $this->to_string();
	}
}

/**
 * A class for implementing a Signature Method
 * See section 9 ("Signing Requests") in the spec
 */
abstract class Twitter_OAuthSignatureMethod {
	/**
	 * Needs to return the name of the Signature Method (ie HMAC-SHA1)
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Build up the signature
	 * NOTE: The output of this function MUST NOT be urlencoded.
	 * the encoding is handled in OAuthRequest when the final
	 * request is serialized
	 * @param  Twitter_OAuthRequest  $request
	 * @param  Twitter_OAuthConsumer $consumer
	 * @param  Twitter_OAuthToken    $token
	 * @return string
	 */
	abstract public function build_signature(
		$request,
		$consumer,
		$token
	);

	/**
	 * Verifies that a given signature is correct
	 * @param  Twitter_OAuthRequest  $request
	 * @param  Twitter_OAuthConsumer $consumer
	 * @param  Twitter_OAuthToken    $token
	 * @param  string                $signature
	 * @return bool
	 */
	public function check_signature(
		$request,
		$consumer,
		$token,
		$signature
	) {
		$built = $this->build_signature( $request, $consumer, $token );

		return $built == $signature;
	}
}

/**
 * The HMAC-SHA1 signature method uses the HMAC-SHA1 signature algorithm as defined in [RFC2104]
 * where the Signature Base String is the text and the key is the concatenated values (each first
 * encoded per Parameter Encoding) of the Consumer Secret and Token Secret, separated by an '&'
 * character (ASCII code 38) even if empty.
 *   - Chapter 9.2 ("HMAC-SHA1")
 */
class Twitter_OAuthSignatureMethod_HMAC_SHA1 extends Twitter_OAuthSignatureMethod {
	public function get_name() {
		return 'HMAC-SHA1';
	}

	/**
	 * @param $request
	 * @param $consumer
	 * @param $token
	 */
	public function build_signature(
		$request,
		$consumer,
		$token
	) {
		$base_string          = $request->get_signature_base_string();
		$request->base_string = $base_string;

		$key_parts = [
			$consumer->secret,
			( $token ) ? $token->secret : '',
		];

		$key_parts = Twitter_OAuthUtil::urlencode_rfc3986( $key_parts );
		$key       = implode( '&', $key_parts );

		return base64_encode( hash_hmac( 'sha1', $base_string, $key, true ) );
	}
}

/**
 * The PLAINTEXT method does not provide any security protection and SHOULD only be used
 * over a secure channel such as HTTPS. It does not use the Signature Base String.
 *   - Chapter 9.4 ("PLAINTEXT")
 */
class Twitter_OAuthSignatureMethod_PLAINTEXT extends Twitter_OAuthSignatureMethod {
	public function get_name() {
		return 'PLAINTEXT';
	}

	/**
	 * oauth_signature is set to the concatenated encoded values of the Consumer Secret and
	 * Token Secret, separated by a '&' character (ASCII code 38), even if either secret is
	 * empty. The result MUST be encoded again.
	 *   - Chapter 9.4.1 ("Generating Signatures")
	 *
	 * Please note that the second encoding MUST NOT happen in the SignatureMethod, as
	 * OAuthRequest handles this!
	 */
	public function build_signature(
		$request,
		$consumer,
		$token
	) {
		$key_parts = [
			$consumer->secret,
			( $token ) ? $token->secret : '',
		];

		$key_parts            = Twitter_OAuthUtil::urlencode_rfc3986( $key_parts );
		$key                  = implode( '&', $key_parts );
		$request->base_string = $key;

		return $key;
	}
}

/**
 * The RSA-SHA1 signature method uses the RSASSA-PKCS1-v1_5 signature algorithm as defined in
 * [RFC3447] section 8.2 (more simply known as PKCS#1), using SHA-1 as the hash function for
 * EMSA-PKCS1-v1_5. It is assumed that the Consumer has provided its RSA public key in a
 * verified way to the Service Provider, in a manner which is beyond the scope of this
 * specification.
 *   - Chapter 9.3 ("RSA-SHA1")
 */
abstract class Twitter_OAuthSignatureMethod_RSA_SHA1 extends Twitter_OAuthSignatureMethod {
	public function get_name() {
		return 'RSA-SHA1';
	}

	// Up to the SP to implement this lookup of keys. Possible ideas are:
	// (1) do a lookup in a table of trusted certs keyed off of consumer
	// (2) fetch via http using a url provided by the requester
	// (3) some sort of specific discovery code based on request
	//
	// Either way should return a string representation of the certificate
	/**
	 * @param $request
	 */
	abstract protected function fetch_public_cert( &$request );

	// Up to the SP to implement this lookup of keys. Possible ideas are:
	// (1) do a lookup in a table of trusted certs keyed off of consumer
	//
	// Either way should return a string representation of the certificate
	/**
	 * @param $request
	 */
	abstract protected function fetch_private_cert( &$request );

	/**
	 * @param $request
	 * @param $consumer
	 * @param $token
	 */
	public function build_signature(
		$request,
		$consumer,
		$token
	) {
		$base_string          = $request->get_signature_base_string();
		$request->base_string = $base_string;

		// Fetch the private key cert based on the request
		$cert = $this->fetch_private_cert( $request );

		// Pull the private key ID from the certificate
		$privatekeyid = openssl_get_privatekey( $cert, null );

		// Sign using the key
		$ok = openssl_sign( $base_string, $signature, $privatekeyid );

		// Release the key resource
		openssl_free_key( $privatekeyid );

		return base64_encode( $signature );
	}

	/**
	 * @param  $request
	 * @param  $consumer
	 * @param  $token
	 * @param  $signature
	 * @return mixed
	 */
	public function check_signature(
		$request,
		$consumer,
		$token,
		$signature
	) {
		$decoded_sig = base64_decode( $signature, true );

		$base_string = $request->get_signature_base_string();

		// Fetch the public key cert based on the request
		$cert = $this->fetch_public_cert( $request );

		// Pull the public key ID from the certificate
		$publickeyid = openssl_get_publickey( $cert );

		// Check the computed signature against the one passed in the query
		$ok = openssl_verify( $base_string, $decoded_sig, $publickeyid );

		// Release the key resource
		openssl_free_key( $publickeyid );

		return 1 == $ok;
	}
}

class Twitter_OAuthRequest {
	// for debug purposes
	/**
	 * @var mixed
	 */
	public $base_string;
	/**
	 * @var string
	 */
	public static $version = '1.0';
	/**
	 * @var string
	 */
	public static $POST_INPUT = 'php://input';
	/**
	 * @var mixed
	 */
	protected $parameters;
	/**
	 * @var mixed
	 */
	protected $http_method;
	/**
	 * @var mixed
	 */
	protected $http_url;

	/**
	 * @param $http_method
	 * @param $http_url
	 * @param $parameters
	 */
	public function __construct(
		$http_method,
		$http_url,
		$parameters = null
	) {
		$parameters        = ( $parameters ) ? $parameters : [];
		$parameters        = array_merge( Twitter_OAuthUtil::parse_parameters( parse_url( $http_url, PHP_URL_QUERY ) ), $parameters );
		$this->parameters  = $parameters;
		$this->http_method = $http_method;
		$this->http_url    = $http_url;
	}

	/**
	 * attempt to build up a request from what was passed to the server
	 */
	public static function from_request(
		$http_method = null,
		$http_url = null,
		$parameters = null
	) {
		$scheme = ( ! isset( $_SERVER['HTTPS'] ) || 'on' != $_SERVER['HTTPS'] )
			? 'http'
			: 'https';
		$http_url = ( $http_url ) ? $http_url : $scheme .
			'://' . $_SERVER['HTTP_HOST'] .
			':' .
			$_SERVER['SERVER_PORT'] .
			$_SERVER['REQUEST_URI'];
		$http_method = ( $http_method ) ? $http_method : $_SERVER['REQUEST_METHOD'];

		// We weren't handed any parameters, so let's find the ones relevant to
		// this request.
		// If you run XML-RPC or similar you should use this to provide your own
		// parsed parameter-list
		if ( ! $parameters ) {
			// Find request headers
			$request_headers = Twitter_OAuthUtil::get_headers();

			// Parse the query-string to find GET parameters
			$parameters = Twitter_OAuthUtil::parse_parameters( $_SERVER['QUERY_STRING'] );

			// It's a POST request of the proper content-type, so parse POST
			// parameters and add those overriding any duplicates from GET
			if ( 'POST' == $http_method && isset( $request_headers['Content-Type'] )
				&& strstr( $request_headers['Content-Type'],
					'application/x-www-form-urlencoded' )
			) {
				$post_data = Twitter_OAuthUtil::parse_parameters(
					file_get_contents( self::$POST_INPUT )
				);
				$parameters = array_merge( $parameters, $post_data );
			}

			// We have a Authorization-header with OAuth data. Parse the header
			// and add those overriding any duplicates from GET or POST
			if ( isset( $request_headers['Authorization'] ) && substr( $request_headers['Authorization'], 0, 6 ) == 'OAuth ' ) {
				$header_parameters = Twitter_OAuthUtil::split_header(
					$request_headers['Authorization']
				);
				$parameters = array_merge( $parameters, $header_parameters );
			}
		}

		return new self( $http_method, $http_url, $parameters );
	}

	/**
	 * pretty much a helper function to set up the request
	 */
	public static function from_consumer_and_token(
		$consumer,
		$token,
		$http_method,
		$http_url,
		$parameters = null
	) {
		$parameters = ( $parameters ) ? $parameters : [];
		$defaults   = ['oauth_version' => self::$version,
			'oauth_nonce'                  => self::generate_nonce(),
			'oauth_timestamp'              => self::generate_timestamp(),
			'oauth_consumer_key'           => $consumer->key];
		if ( $token ) {
			$defaults['oauth_token'] = $token->key;
		}

		$parameters = array_merge( $defaults, $parameters );

		return new self( $http_method, $http_url, $parameters );
	}

	/**
	 * @param $name
	 * @param $value
	 * @param $allow_duplicates
	 */
	public function set_parameter(
		$name,
		$value,
		$allow_duplicates = true
	) {
		if ( $allow_duplicates && isset( $this->parameters[$name] ) ) {
			// We have already added parameter(s) with this name, so add to the list
			if ( is_scalar( $this->parameters[$name] ) ) {
				// This is the first duplicate, so transform scalar (string)
				// into an array so we can add the duplicates
				$this->parameters[$name] = [$this->parameters[$name]];
			}

			$this->parameters[$name][] = $value;
		} else {
			$this->parameters[$name] = $value;
		}
	}

	/**
	 * @param $name
	 */
	public function get_parameter( $name ) {
		return isset( $this->parameters[$name] ) ? $this->parameters[$name] : null;
	}

	/**
	 * @return mixed
	 */
	public function get_parameters() {
		return $this->parameters;
	}

	/**
	 * @param $name
	 */
	public function unset_parameter( $name ) {
		unset( $this->parameters[$name] );
	}

	/**
	 * The request parameters, sorted and concatenated into a normalized string.
	 * @return string
	 */
	public function get_signable_parameters() {
		// Grab all parameters
		$params = $this->parameters;

		// Remove oauth_signature if present
		// Ref: Spec: 9.1.1 ("The oauth_signature parameter MUST be excluded.")
		if ( isset( $params['oauth_signature'] ) ) {
			unset( $params['oauth_signature'] );
		}

		return Twitter_OAuthUtil::build_http_query( $params );
	}

	/**
	 * Returns the base string of this request
	 *
	 * The base string defined as the method, the url
	 * and the parameters (normalized), each urlencoded
	 * and the concated with &.
	 */
	public function get_signature_base_string() {
		$parts = [
			$this->get_normalized_http_method(),
			$this->get_normalized_http_url(),
			$this->get_signable_parameters(),
		];

		$parts = Twitter_OAuthUtil::urlencode_rfc3986( $parts );

		return implode( '&', $parts );
	}

	/**
	 * just uppercases the http method
	 */
	public function get_normalized_http_method() {
		return strtoupper( $this->http_method );
	}

	/**
	 * parses the url and rebuilds it to be
	 * scheme://host/path
	 */
	public function get_normalized_http_url() {
		$parts = parse_url( $this->http_url );

		$scheme = ( isset( $parts['scheme'] ) ) ? $parts['scheme'] : 'http';
		$port   = ( isset( $parts['port'] ) ) ? $parts['port'] : (  ( 'https' == $scheme ) ? '443' : '80' );
		$host   = ( isset( $parts['host'] ) ) ? $parts['host'] : '';
		$path   = ( isset( $parts['path'] ) ) ? $parts['path'] : '';

		if (  ( 'https' == $scheme && '443' != $port )
			|| ( 'http' == $scheme && '80' != $port ) ) {
			$host = "$host:$port";
		}

		return "$scheme://$host$path";
	}

	/**
	 * builds a url usable for a GET request
	 */
	public function to_url() {
		$post_data = $this->to_postdata();
		$out       = $this->get_normalized_http_url();
		if ( $post_data ) {
			$out .= '?' . $post_data;
		}

		return $out;
	}

	/**
	 * builds the data one would send in a POST request
	 */
	public function to_postdata() {
		return Twitter_OAuthUtil::build_http_query( $this->parameters );
	}

	/**
	 * builds the Authorization: header
	 */
	public function to_header( $realm = null ) {
		$first = true;
		if ( $realm ) {
			$out   = 'Authorization: OAuth realm="' . Twitter_OAuthUtil::urlencode_rfc3986( $realm ) . '"';
			$first = false;
		} else {
			$out = 'Authorization: OAuth';
		}

		$total = [];
		foreach ( $this->parameters as $k => $v ) {
			if ( substr( $k, 0, 5 ) != 'oauth' ) {
				continue;
			}
			if ( is_array( $v ) ) {
				throw new Twitter_OAuthException( 'Arrays not supported in headers' );
			}
			$out .= ( $first ) ? ' ' : ',';
			$out .= Twitter_OAuthUtil::urlencode_rfc3986( $k ) .
			'="' .
			Twitter_OAuthUtil::urlencode_rfc3986( $v ) .
				'"';
			$first = false;
		}

		return $out;
	}

	/**
	 * @return mixed
	 */
	public function __toString() {
		return $this->to_url();
	}

	/**
	 * @param $signature_method
	 * @param $consumer
	 * @param $token
	 */
	public function sign_request(
		$signature_method,
		$consumer,
		$token
	) {
		$this->set_parameter(
			'oauth_signature_method',
			$signature_method->get_name(),
			false
		);
		$signature = $this->build_signature( $signature_method, $consumer, $token );
		$this->set_parameter( 'oauth_signature', $signature, false );
	}

	/**
	 * @param  $signature_method
	 * @param  $consumer
	 * @param  $token
	 * @return mixed
	 */
	public function build_signature(
		$signature_method,
		$consumer,
		$token
	) {
		$signature = $signature_method->build_signature( $this, $consumer, $token );

		return $signature;
	}

	/**
	 * util function: current timestamp
	 */
	private static function generate_timestamp() {
		return time();
	}

	/**
	 * util function: current nonce
	 */
	private static function generate_nonce() {
		$mt   = microtime();
		$rand = mt_rand();

		return md5( $mt . $rand ); // md5s look nicer than numbers
	}
}

class Twitter_OAuthServer {
	/**
	 * @var int
	 */
	protected $timestamp_threshold = 300; // in seconds, five minutes
	/**
	 * @var string
	 */
	protected $version = '1.0'; // hi blaine
	/**
	 * @var array
	 */
	protected $signature_methods = [];

	/**
	 * @var mixed
	 */
	protected $data_store;

	/**
	 * @param $data_store
	 */
	public function __construct( $data_store ) {
		$this->data_store = $data_store;
	}

	/**
	 * @param $signature_method
	 */
	public function add_signature_method( $signature_method ) {
		$this->signature_methods[$signature_method->get_name()] =
			$signature_method;
	}

	// high level functions

	/**
	 * process a request_token request
	 * returns the request token on success
	 */
	public function fetch_request_token( &$request ) {
		$this->get_version( $request );

		$consumer = $this->get_consumer( $request );

		// no token required for the initial token request
		$token = null;

		$this->check_signature( $request, $consumer, $token );

		// Rev A change
		$callback  = $request->get_parameter( 'oauth_callback' );
		$new_token = $this->data_store->new_request_token( $consumer, $callback );

		return $new_token;
	}

	/**
	 * process an access_token request
	 * returns the access token on success
	 */
	public function fetch_access_token( &$request ) {
		$this->get_version( $request );

		$consumer = $this->get_consumer( $request );

		// requires authorized request token
		$token = $this->get_token( $request, $consumer, 'request' );

		$this->check_signature( $request, $consumer, $token );

		// Rev A change
		$verifier  = $request->get_parameter( 'oauth_verifier' );
		$new_token = $this->data_store->new_access_token( $token, $consumer, $verifier );

		return $new_token;
	}

	/**
	 * verify an api call, checks all the parameters
	 */
	public function verify_request( &$request ) {
		$this->get_version( $request );
		$consumer = $this->get_consumer( $request );
		$token    = $this->get_token( $request, $consumer, 'access' );
		$this->check_signature( $request, $consumer, $token );

		return [$consumer, $token];
	}

	// Internals from here

/**
 * version 1
 */
	private function get_version( &$request ) {
		$version = $request->get_parameter( 'oauth_version' );
		if ( ! $version ) {
			// Service Providers MUST assume the protocol version to be 1.0 if this parameter is not present.
			// Chapter 7.0 ("Accessing Protected Ressources")
			$version = '1.0';
		}
		if ( $version !== $this->version ) {
			throw new Twitter_OAuthException( "OAuth version '$version' not supported" );
		}

		return $version;
	}

	/**
	 * figure out the signature with some defaults
	 */
	private function get_signature_method( $request ) {
		$signature_method = $request instanceof Twitter_OAuthRequest
			? $request->get_parameter( 'oauth_signature_method' )
			: null;

		if ( ! $signature_method ) {
			// According to chapter 7 ("Accessing Protected Ressources") the signature-method
			// parameter is required, and we can't just fallback to PLAINTEXT
			throw new Twitter_OAuthException( 'No signature method parameter. This parameter is required' );
		}

		if ( ! in_array( $signature_method,
			array_keys( $this->signature_methods ), true ) ) {
			throw new Twitter_OAuthException(
				"Signature method '$signature_method' not supported " .
				'try one of the following: ' .
				implode( ', ', array_keys( $this->signature_methods ) )
			);
		}

		return $this->signature_methods[$signature_method];
	}

	/**
	 * try to find the consumer for the provided request's consumer key
	 */
	private function get_consumer( $request ) {
		$consumer_key = $request instanceof Twitter_OAuthRequest
			? $request->get_parameter( 'oauth_consumer_key' )
			: null;

		if ( ! $consumer_key ) {
			throw new Twitter_OAuthException( 'Invalid consumer key' );
		}

		$consumer = $this->data_store->lookup_consumer( $consumer_key );
		if ( ! $consumer ) {
			throw new Twitter_OAuthException( 'Invalid consumer' );
		}

		return $consumer;
	}

	/**
	 * try to find the token for the provided request's token key
	 */
	private function get_token(
		$request,
		$consumer,
		$token_type = 'access'
	) {
		$token_field = $request instanceof Twitter_OAuthRequest
			? $request->get_parameter( 'oauth_token' )
			: null;

		$token = $this->data_store->lookup_token(
			$consumer, $token_type, $token_field
		);
		if ( ! $token ) {
			throw new Twitter_OAuthException( "Invalid $token_type token: $token_field" );
		}

		return $token;
	}

	/**
	 * all-in-one function to check the signature on a request
	 * should guess the signature method appropriately
	 */
	private function check_signature(
		$request,
		$consumer,
		$token
	) {
		// this should probably be in a different method
		$timestamp = $request instanceof Twitter_OAuthRequest
			? $request->get_parameter( 'oauth_timestamp' )
			: null;
		$nonce = $request instanceof Twitter_OAuthRequest
			? $request->get_parameter( 'oauth_nonce' )
			: null;

		$this->check_timestamp( $timestamp );
		$this->check_nonce( $consumer, $token, $nonce, $timestamp );

		$signature_method = $this->get_signature_method( $request );

		$signature = $request->get_parameter( 'oauth_signature' );
		$valid_sig = $signature_method->check_signature(
			$request,
			$consumer,
			$token,
			$signature
		);

		if ( ! $valid_sig ) {
			throw new Twitter_OAuthException( 'Invalid signature' );
		}
	}

	/**
	 * check that the timestamp is new enough
	 */
	private function check_timestamp( $timestamp ) {
		if ( ! $timestamp ) {
			throw new Twitter_OAuthException(
				'Missing timestamp parameter. The parameter is required'
			);
		}

		// verify that timestamp is recentish
		$now = time();
		if ( abs( $now - $timestamp ) > $this->timestamp_threshold ) {
			throw new Twitter_OAuthException(
				"Expired timestamp, yours $timestamp, ours $now"
			);
		}
	}

	/**
	 * check that the nonce is not repeated
	 */
	private function check_nonce(
		$consumer,
		$token,
		$nonce,
		$timestamp
	) {
		if ( ! $nonce ) {
			throw new Twitter_OAuthException(
				'Missing nonce parameter. The parameter is required'
			);
		}

		// verify that the nonce is uniqueish
		$found = $this->data_store->lookup_nonce(
			$consumer,
			$token,
			$nonce,
			$timestamp
		);
		if ( $found ) {
			throw new Twitter_OAuthException( "Nonce already used: $nonce" );
		}
	}
}

class Twitter_OAuthDataStore {
	/**
	 * @param $consumer_key
	 */
	public function lookup_consumer( $consumer_key ) {
		// implement me
	}

	/**
	 * @param $consumer
	 * @param $token_type
	 * @param $token
	 */
	public function lookup_token(
		$consumer,
		$token_type,
		$token
	) {
		// implement me
	}

	/**
	 * @param $consumer
	 * @param $token
	 * @param $nonce
	 * @param $timestamp
	 */
	public function lookup_nonce(
		$consumer,
		$token,
		$nonce,
		$timestamp
	) {
		// implement me
	}

	/**
	 * @param $consumer
	 * @param $callback
	 */
	public function new_request_token(
		$consumer,
		$callback = null
	) {
		// return a new token attached to this consumer
	}

	/**
	 * @param $token
	 * @param $consumer
	 * @param $verifier
	 */
	public function new_access_token(
		$token,
		$consumer,
		$verifier = null
	) {
		// return a new access token attached to this consumer
		// for the user associated with this token if the request token
		// is authorized
		// should also invalidate the request token
	}
}

class Twitter_OAuthUtil {
	/**
	 * @param $input
	 */
	public static function urlencode_rfc3986( $input ) {
		if ( is_array( $input ) ) {
			return array_map( ['Twitter_OAuthUtil', 'urlencode_rfc3986'], $input );
		} else {
			if ( is_scalar( $input ) ) {
				return str_replace(
					'+',
					' ',
					str_replace( '%7E', '~', rawurlencode( $input ) )
				);
			} else {
				return '';
			}
		}
	}

	// This decode function isn't taking into consideration the above
	// modifications to the encoding process. However, this method doesn't
	// seem to be used anywhere so leaving it as is.
	/**
	 * @param $string
	 */
	public static function urldecode_rfc3986( $string ) {
		return urldecode( $string );
	}

	// Utility function for turning the Authorization: header into
	// parameters, has to do some unescaping
	// Can filter out any non-oauth parameters if needed (default behaviour)
	// May 28th, 2010 - method updated to tjerk.meesters for a speed improvement.
	//                  see http://code.google.com/p/oauth/issues/detail?id=163
	/**
	 * @param  $header
	 * @param  $only_allow_oauth_parameters
	 * @return mixed
	 */
	public static function split_header(
		$header,
		$only_allow_oauth_parameters = true
	) {
		$params = [];
		if ( preg_match_all( '/(' . ( $only_allow_oauth_parameters ? 'oauth_' : '' ) . '[a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $header, $matches ) ) {
			foreach ( $matches[1] as $i => $h ) {
				$params[$h] = self::urldecode_rfc3986( empty( $matches[3][$i] ) ? $matches[4][$i] : $matches[3][$i] );
			}
			if ( isset( $params['realm'] ) ) {
				unset( $params['realm'] );
			}
		}

		return $params;
	}

	// helper to try to sort out headers for people who aren't running apache
	/**
	 * @return mixed
	 */
	public static function get_headers() {
		if ( function_exists( 'apache_request_headers' ) ) {
			// we need this to get the actual Authorization: header
			// because apache tends to tell us it doesn't exist
			$headers = apache_request_headers();

			// sanitize the output of apache_request_headers because
			// we always want the keys to be Cased-Like-This and arh()
			// returns the headers in the same case as they are in the
			// request
			$out = [];
			foreach ( $headers as $key => $value ) {
				$key = str_replace(
					' ',
					'-',
					ucwords( strtolower( str_replace( '-', ' ', $key ) ) )
				);
				$out[$key] = $value;
			}
		} else {
			// otherwise we don't have apache and are just going to have to hope
			// that $_SERVER actually contains what we need
			$out = [];
			if ( isset( $_SERVER['CONTENT_TYPE'] ) ) {
				$out['Content-Type'] = $_SERVER['CONTENT_TYPE'];
			}
			if ( isset( $_ENV['CONTENT_TYPE'] ) ) {
				$out['Content-Type'] = $_ENV['CONTENT_TYPE'];
			}

			foreach ( $_SERVER as $key => $value ) {
				if ( substr( $key, 0, 5 ) == 'HTTP_' ) {
					// this is chaos, basically it is just there to capitalize the first
					// letter of every word that is not an initial HTTP and strip HTTP
					// code from przemek
					$key = str_replace(
						' ',
						'-',
						ucwords( strtolower( str_replace( '_', ' ', substr( $key, 5 ) ) ) )
					);
					$out[$key] = $value;
				}
			}
		}

		return $out;
	}

	// This function takes a input like a=b&a=c&d=e and returns the parsed
	// parameters like this
	// ['a' => array('b','c'), 'd' => 'e']
	/**
	 * @param  $input
	 * @return mixed
	 */
	public static function parse_parameters( $input ) {
		if ( ! isset( $input ) || ! $input ) {
			return [];
		}

		$pairs = explode( '&', $input );

		$parsed_parameters = [];
		foreach ( $pairs as $pair ) {
			$split     = explode( '=', $pair, 2 );
			$parameter = self::urldecode_rfc3986( $split[0] );
			$value     = isset( $split[1] ) ? self::urldecode_rfc3986( $split[1] ) : '';

			if ( isset( $parsed_parameters[$parameter] ) ) {
				// We have already recieved parameter(s) with this name, so add to the list
				// of parameters with this name

				if ( is_scalar( $parsed_parameters[$parameter] ) ) {
					// This is the first duplicate, so transform scalar (string) into an array
					// so we can add the duplicates
					$parsed_parameters[$parameter] = [$parsed_parameters[$parameter]];
				}

				$parsed_parameters[$parameter][] = $value;
			} else {
				$parsed_parameters[$parameter] = $value;
			}
		}

		return $parsed_parameters;
	}

	/**
	 * @param $params
	 */
	public static function build_http_query( $params ) {
		if ( ! $params ) {
			return '';
		}

		// Urlencode both keys and values
		$keys   = self::urlencode_rfc3986( array_keys( $params ) );
		$values = self::urlencode_rfc3986( array_values( $params ) );
		$params = array_combine( $keys, $values );

		// Parameters are sorted by name, using lexicographical byte value ordering.
		// Ref: Spec: 9.1.1 (1)
		uksort( $params, 'strcmp' );

		$pairs = [];
		foreach ( $params as $parameter => $value ) {
			if ( is_array( $value ) ) {
				// If two or more parameters share the same name, they are sorted by their value
				// Ref: Spec: 9.1.1 (1)
				// June 12th, 2010 - changed to sort because of issue 164 by hidetaka
				sort( $value, SORT_STRING );
				foreach ( $value as $duplicate_value ) {
					$pairs[] = $parameter . '=' . $duplicate_value;
				}
			} else {
				$pairs[] = $parameter . '=' . $value;
			}
		}
		// For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
		// Each name-value pair is separated by an '&' character (ASCII code 38)

		return implode( '&', $pairs );
	}
}

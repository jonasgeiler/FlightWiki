<?php


/**
 * Csrf
 *
 * Handles CSRF tokens and prevents cross-site request forgery
 *
 */
class Csrf {

	/**
	 * CSRF Token
	 *
	 * @var array
	 */
	private $token = [
		'name'           => null,
		'value'          => null,
		'expirationTime' => null,
	];

	/**
	 * Create a new token
	 *
	 * @param string $tokenName
	 * @param int    $tokenExpirationTime
	 *
	 * @return array
	 */
	public function createToken ($tokenName = 'form_token', $tokenExpirationTime = 300) {
		if (isset($_SESSION[$tokenName])) {
			$this->token['name'] = $tokenName;
			$this->token['value'] = $_SESSION[$tokenName];
			$this->token['expirationTime'] = $_SESSION["{$tokenName}_expiration_time"];
		} else {
			if (!is_null($this->token['name']))
				$this->unsetToken($this->token); // Unset previous token

			$this->token['name'] = $tokenName;
			$this->token['value'] = md5(uniqid('auth', true));

			if (is_null($this->token['expirationTime']))
				$this->token['expirationTime'] = $tokenExpirationTime;

			$_SESSION[$tokenName] = $this->token['value'];
			$_SESSION["{$tokenName}_time"] = time();
			$_SESSION["{$tokenName}_expiration_time"] = $this->token['expirationTime'];
		}

		return $this->token;
	}

	/**
	 * Unset an existing token in the session data
	 *
	 * @param null|array|string $token
	 */
	public function unsetToken($token = null) {
		if (is_null($token))
			$token = $this->createToken();

		$tokenName = is_array($token) ? $token['name'] : $token;

		if (!is_null($tokenName)) {
			unset($_SESSION[$tokenName]);
			unset($_SESSION["{$tokenName}_time"]);
			unset($_SESSION["{$tokenName}_expiration_time"]);
		}
	}

	/**
	 * Get the age of a token
	 *
	 * @param null|array|string $token
	 *
	 * @return int
	 */
	public function getTokenAge($token = null) {
		if (is_null($token))
			$token = $this->createToken();

		$tokenName = is_array($token) ? $token['name'] : $token;

		return time() - $_SESSION["{$tokenName}_time"];
	}

	/**
	 * Check if a CSRF Token has expired
	 *
	 * @param null|array $token
	 *
	 * @return bool
	 */
	public function hasTokenExpired($token = null) {
		if (is_null($token))
			$token = $this->createToken();

		return $this->getTokenAge($token) > $token['expirationTime'];
	}

	/**
	 * Check if a CSRF Token is valid
	 *
	 * @param string $msg
	 * @param null|array|string $token
	 *
	 * @return bool
	 */
	public function requireValidToken($msg = 'Cross site request forgery detected. Request aborted!', $token = null) {
		if (is_null($token))
			$token = $this->createToken();

		$tokenName = is_array($token) ? $token['name'] : $token;

		if (Flight::request()->data[$tokenName] != $_SESSION[$tokenName])
			Flight::halt(403, $msg);

		return true;
	}

	/**
	 * Render an input element which adds the CSRF Token to forms
	 *
	 * @param null|array|string $token
	 *
	 * @return string
	 */
	public function renderFormTokenField($token = null) {
		if (is_null($token))
			$token = $this->createToken();

		$tokenValue = is_array($token) ? $token['value'] : $token;

		return '<input type="hidden" name="form_token" value="' . $tokenValue . '" id="form_token">';
	}

}
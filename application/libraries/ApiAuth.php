<?php

class ApiAuth
{
	/* Simple token based API authentication library
	Author: sanjeev sharma
	Email:  codextimeindia@gmail.com
	*/
	private $encryptKey = 'gdgdsnfg54n5t594g54gn4g04gj09j5gh043gh3403hj'; //random generated secret key
	private $secretIV = '5g8g5jg59jjhgh569538545t90yjyh04yj4y606ygfmhkgf'; //random generated initialization vector

	private $isTokenExpire = true; //set true for token expiry, false for never expire
	private $tokenExpriryInHours = 24; //token expiry time in Hours


	private $encryptionMethod = "AES-256-CBC";
	private $output = null;


	public function encrypt($string)
	{
		$key = hash('sha256', $this->encryptKey);
		$iv = substr(hash('sha256', $this->secretIV), 0, 16);
		return openssl_encrypt($string, $this->encryptionMethod, $key, 0, $iv);
	}

	public function decrypt($string)
	{
		$key = hash('sha256', $this->encryptKey);
		$iv = substr(hash('sha256', $this->secretIV), 0, 16);
		return openssl_decrypt($string, $this->encryptionMethod, $key, 0, $iv);
	}

	public function generateToken($userId)
	{
		$tokenString = base64_encode(openssl_random_pseudo_bytes(64));
		$token = strtr($tokenString, '+/', '-_');
		$mainToken = hash('sha256', $token);

		//Guardar el token en la base de datos (cuando sea necesario)
		//$this->storeTokenInAuthTokens($userId, $mainToken);
		return $mainToken . '.' . $this->encrypt($userId);
	}

	/* check user authentication */
	public function isNotAuthenticated()
	{
		$mainToken = $this->getMainToken();
		//var_dump($mainToken);exit;
		if ($mainToken) {
			$userId = $this->getUserId();
			return $this->checkTokenFromUserTable($userId, $mainToken);
		} else {
			return false;
		}

	}


	/* get User Id */

	public function getUserId()
	{
		if ($this->getTokenParts()) {
			$tokenPart = $this->getTokenParts();
			//print_r($tokenPart);exit;
			$userIdToken = $tokenPart[1];
			return $this->decrypt($userIdToken);
		} else {
			echo 'Token parts Error!';
			exit;
		}
	}


	/* get Token parts */
	public function getTokenParts()
	{

		$bearerToken = $this->getBearerToken();

		if ($bearerToken == null) {
			$err = array(
				'status' => false,
				'message' => 'Token not found',
			);
			return json_encode($err);

		} else {
			return explode(".", $bearerToken);

		}
	}


	/* Get Main Token */
	public function getMainToken()
	{
		if ($this->getTokenParts()) {
			$tokenPart = $this->getTokenParts();
			return $tokenPart[0];
		} else {
			return false;
		}
	}


	/**
	 * Get header Authorization
	 * */
	function getAuthorizationHeader()
	{
		$headers = null;
		if (isset($_SERVER['Authorization'])) {
			$headers = trim($_SERVER["Authorization"]);
		} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
			$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
		} elseif (function_exists('apache_request_headers')) {
			$requestHeaders = apache_request_headers();
			// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
			$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
			//print_r($requestHeaders);
			if (isset($requestHeaders['Authorization'])) {
				$headers = trim($requestHeaders['Authorization']);
			}
		}

		return $headers;
	}

	/**
	 * get access token from header
	 * */
	function getBearerToken()
	{
		$headers = $this->getAuthorizationHeader();

		// HEADER: Get the access token from the header
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}

		return null;
	}

	/* create auth_tokens table by using this Schema
	"CREATE TABLE `auth_tokens` ( `id` INT NOT NULL AUTO_INCREMENT ,
	`user_id` INT(11) NOT NULL , `token` VARCHAR(255) NOT NULL ,
	`expiry_date` VARCHAR(50) NOT NULL ,
	`created_at` VARCHAR(50) NOT NULL ,
	 PRIMARY KEY (`id`)) ENGINE = InnoDB;"
	*/

	function checkTokenFromUserTable($userId, $mainToken)
	{
		$CI = &get_instance();  //Store instance in a variable.
		$CI->load->database();
		$CI->db->select('*');
		$CI->db->from('auth_tokens');
		$CI->db->where(['user_id' => $userId, 'token' => $mainToken]);
		if ($this->isTokenExpire) {
			$expiryDate = date('Y-m-d H:i:s');
			$CI->db->where('expiry_date >=', $expiryDate);
		}
		$query = $CI->db->get();

		//echo $query->num_rows();exit;
		if ($query->num_rows() == 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function storeTokenInAuthTokens($userId, $token)
	{
		$expirtyDate = $this->getTimeAfterHours($this->tokenExpriryInHours);
		$CI = &get_instance();  //Store instance in a variable.
		$CI->load->database();
		if ($CI->db->query("SELECT * FROM auth_tokens WHERE user_id='$userId'")->num_rows() > 0) {
			$CI->db->where('user_id', $userId);
			$CI->db->update('auth_tokens', ['expiry_date' => $expirtyDate, 'user_id' => $userId, 'token' => $token, 'created_at' => date('Y-m-d H:i')]);
		} else {
			$CI->db->insert('auth_tokens', ['expiry_date' => $expirtyDate, 'user_id' => $userId, 'token' => $token, 'created_at' => date('Y-m-d H:i')]);
		}
	}

	function getTimeAfterHours($hours)
	{
		if ($hours != null || $hours != 0) {
			return date('Y-m-d H:i:s', strtotime($hours . ' hour'));
		} else {
			$hours = 24; //setting default time
			return date('Y-m-d H:i:s', strtotime($hours . ' hour'));
		}
	}


}

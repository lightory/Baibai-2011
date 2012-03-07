<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Defines the different OAuth Signing algorithms. You 
 * should use this instead of writing them out each time.
 */
class OAUTH_ALGORITHMS
{
    const HMAC_SHA1 = 'HMAC-SHA1';
    const RSA_SHA1 = 'RSA-SHA1';
}

/**
 * Signs an array of oauth parameters according to the 1.0 spec using
 * the hmac-sha1 hasing algorithm
 *
 * @param string $method either GET or POST
 * @param string $baseurl the baseurl we are authenticating againts
 * @param string $secret the consumer secret key
 * @param array $parameters all parameters that need to be signed (NOTE: the token secret key should be added here)
 * @return string the signature
 */
function sign_hmac_sha1($method, $baseurl, $secret, array $parameters)
{
    $data = $method.'&';
    $data .= urlencode($baseurl).'&';
    $oauth = '';
    ksort($parameters);
    //Put the token secret in if it does not exist. It
    //will be empty if it does not exist as per the spec.
    if(!array_key_exists('oauth_token_secret', $parameters))
			$parameters['oauth_token_secret'] = '';
		
    foreach($parameters as $key => $value)
    {
        //Don't include the token secret into the base string
        if(strtolower($key) != 'oauth_token_secret')
					$oauth .= "&{$key}={$value}";
    }    
    $data .= urlencode(substr($oauth, 1));
    $secret .= '&'.$parameters['oauth_token_secret'];
		//echo $data.'<br/><br/>';
		//echo $secret.'<br/><br/>';
		//echo base64_encode(hash_hmac('sha1', $data, $secret, true)).'<br/><br/>';
    
    return base64_encode(hash_hmac('sha1', $data, $secret, true));
}

/**
 * Signs an array of oauth parameters according to the 1.0 spec using
 * the rsa-sha1 hasing algorithm
 *
 * @param string $method either GET or POST
 * @param string $baseurl the baseurl we are authenticating againts
 * @param string $certfile the location of your private certificate file
 * @param array $parameters all parameters that need to be signed
 * @return string the signature
 */
function sign_rsa_sha1($method, $baseurl, $certfile, array $parameters)
{
    $fp = fopen($certfile, "r");
    $private = fread($fp, 8192);
    fclose($fp);

    $data = $method.'&';
    $data .= urlencode($baseurl).'&';
    $oauth = '';
    ksort($parameters);

    foreach($parameters as $key => $value)
        $oauth .= "&{$key}={$value}";
    $data .= urlencode(substr($oauth, 1));

    $keyid = openssl_get_privatekey($private);
    openssl_sign($data, $signature, $keyid);
    openssl_free_key($keyid);

    return base64_encode($signature);
}

/**
 * Assembles the auth params array into a string that can
 * be put into an http header request.
 *
 * @param array $authparams the oauth parameters
 * @return string the header authorization portion with trailing \r\n
 */
function build_auth_string(array $authparams)
{
    $header = "Authorization: OAuth ";
    $auth = '';
    foreach($authparams AS $key=>$value)
    {
        //Don't include token secret
        if($key != 'oauth_token_secret')$auth .= ",{$key}=\"{$value}\"";
    }
    return $header.substr($auth, 1)."\r\n";
}

/**
 * Assemble an associative array with oauth values
 *
 * @param string $baseurl the base url we are authenticating against.
 * @param string $key your consumer key
 * @param string $secret either your consumer secret key or the file location of your rsa private key.
 * @param array $extra additional oauth parameters that should be included (you must urlencode, if appropriate, before calling this function)
 * @param string $method either GET or POST
 * @param string $algo either HMAC-SHA1 or RSA-SHA1 (NOTE: this affects what you put in for the secret parameter)
 * @return array of all the oauth parameters
 */
function build_auth_array($baseurl, $key, $secret, $extra = array(), $method = 'GET', $algo = OAUTH_ALGORITHMS::HMAC_SHA1)
{
    $auth['oauth_consumer_key'] = $key;
    $auth['oauth_signature_method'] = $algo;
    $auth['oauth_timestamp'] = time();
    $auth['oauth_nonce'] = md5(uniqid(rand(), true));
    $auth['oauth_version'] = '1.0';

    $auth = array_merge($auth, $extra);
    if(strtoupper($algo) == OAUTH_ALGORITHMS::HMAC_SHA1)$auth['oauth_signature'] = sign_hmac_sha1($method, $baseurl, $secret, $auth);
    else if(strtoupper($algo) == OAUTH_ALGORITHMS::RSA_SHA1)$auth['oauth_signature'] = sign_rsa_sha1 ($method, $baseurl, $secret, $auth);
  
    $auth['oauth_signature'] = urlencode($auth['oauth_signature']);
    return $auth;
}

/**
 * Creates the authorization portion of a header NOTE: This does not
 * create a complete http header. Also NOTE: the oauth_token parameter
 * should be passed in using the $extra array.
 *
 * @param string $baseurl the base url we are authenticating against.
 * @param string $key your consumer key
 * @param string $secret either your consumer secret key or the file location of your rsa private key.
 * @param array $extra additional oauth parameters that should be included (you must urlencode a parameter, if appropriate, before calling this function)
 * @param string $method either GET or POST
 * @param string $algo either HMAC-SHA1 or RSA-SHA1 (NOTE: this affects what you put in for the secret parameter)
 * @return string the header authorization portion with trailing \r\n
 */
function get_auth_header($baseurl, $key, $secret, $extra = array(), $method = 'GET', $algo = OAUTH_ALGORITHMS::RSA_SHA1)
{
    $auth = build_auth_array($baseurl, $key, $secret, $extra, $method, $algo);
    return build_auth_string($auth);
}

function parse_token_to_array($response){
	$response = explode('&', $response);
	$token = array();
	foreach($response as $temp){
		$temp = explode('=', $temp);
		$token[$temp[0]] = $temp[1];
	}
	return $token;
}

function upload_tsina($status, $imgUrl, $userOAuth) {
	$baseurl = 'http://api.t.sina.com.cn/statuses/upload.json';
	$appKey = '1470536138';
	$appSecret = '0051ebf056d66dad5a26c968080420f0';
	
	$status = rawurlencode($status);
	
	$extra = array(
		'oauth_token' => $userOAuth->access_token,
		'oauth_token_secret' => $userOAuth->access_token_secret,
		'status' => $status
	);
	$auth = build_auth_array($baseurl, $appKey, $appSecret, $extra, 'POST');
	$str = '';
	foreach($auth AS $key=>$value)
		if((strtolower($key) != 'oauth_token_secret') && (strtolower($key) != 'status'))
			$str .= ", {$key}=\"{$value}\"";
	$str = substr($str, 2);//Remove leading ,
	//echo $str.'<br/><br/>';
	
	$fields = array(
		'status' => $status,
		'pic' => file_get_contents($imgUrl)
	);
	$fields_string = '';
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string,'&');

	$ch = curl_init("{$baseurl}");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}"));
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	$response = curl_exec($ch);
	curl_close($ch);
	
	echo $response;
}

function update_tsina($status, $userOAuth){
	$baseurl = 'http://api.t.sina.com.cn/statuses/update.json';
	$appKey = '1470536138';
	$appSecret = '0051ebf056d66dad5a26c968080420f0';
	
	$status = rawurlencode($status);
	
	$extra = array(
		'oauth_token' => $userOAuth->access_token,
		'oauth_token_secret' => $userOAuth->access_token_secret,
		'status' => $status
	);
	$auth = build_auth_array($baseurl, $appKey, $appSecret, $extra, 'POST');
	$str = '';
	foreach($auth AS $key=>$value)
		if((strtolower($key) != 'oauth_token_secret') && (strtolower($key) != 'status'))
			$str .= ", {$key}=\"{$value}\"";
	$str = substr($str, 2);//Remove leading ,
	//echo $str.'<br/><br/>';
	
	$fields = array(
		'status' => $status,
		//'pic' => '@include/style/img/404_bg.png'
	);
	$fields_string = '';
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string,'&');

	$ch = curl_init("{$baseurl}");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}"));
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	$response = curl_exec($ch);
	curl_close($ch);
	
	//echo $response;
}

function update_douban($status, $userOAuth){
	$baseurl = 'http://api.douban.com/miniblog/saying';
	$appKey = '05550a4a098bad3d0fff1ede1ca307b7';
	$appSecret = 'd860bf42aaeeab8b';
	
	$extra = array(
		'oauth_token' => $userOAuth->access_token,
		'oauth_token_secret' => $userOAuth->access_token_secret,
		'status' => rawurlencode($status)
	);
	$auth = build_auth_array($baseurl, $appKey, $appSecret, $extra, 'POST');
	$str = '';
	foreach($auth AS $key=>$value)
		if((strtolower($key) != 'oauth_token_secret'))
			$str .= ", {$key}=\"{$value}\"";
	$str = substr($str, 2);//Remove leading ,
	//echo $str.'<br/><br/>';
	
	$post_data = '<?xml version=\'1.0\' encoding=\'UTF-8\'?>'.
					'<entry xmlns:ns0="http://www.w3.org/2005/Atom" '.
						'xmlns:db="http://www.douban.com/xmlns/">'.
					'<content>'.$status.'</content>'.
					'</entry>';

	$ch = curl_init("{$baseurl}");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}", "Content-Type: application/atom+xml"));
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$response = curl_exec($ch);
	curl_close($ch);
	
	//echo $response;
}

function update_tqq($status, $userOAuth){
	$baseurl = 'http://open.t.qq.com/api/t/add';
	$appKey = '2595e7182a3d445f93778287e9a869f2';
	$appSecret = '482918146ad547ef6c00d3235e3056f7';
	
	$status = rawurlencode($status);
	
	$extra = array(
		'oauth_token' => $userOAuth->access_token,
		'oauth_token_secret' => $userOAuth->access_token_secret,
		'content' => $status,
		'format' => 'json',
		//'clientip' => '127.0.0.1',
		//'jing' => '110.5',
		//'wei' => '23.4',
	);
	$auth = build_auth_array($baseurl, $appKey, $appSecret, $extra, 'POST');
	$str = '';
	foreach($auth AS $key=>$value)
		if((strtolower($key) != 'oauth_token_secret'))
			$str .= "&{$key}={$value}";//Do not include scope in the Authorization string.
	$str = substr($str, 1);//Remove leading ,
	//echo $str.'<br/><br/>';
	//echo "{$baseurl}?{$str}".'<br/><br/>';
	
	$fields = array(
		'format' => 'json',
		'content' => $status,
		//'clientip' => '127.0.0.1',
		//'jing' => '110.5',
		//'wei' => '23.4',
		//'pic' => '@include/style/img/404_bg.png'
	);
	$fields_string = '';
	foreach($fields as $key=>$value) { 
		$fields_string .= $key.'='.$value.'&'; 
	}
	$fields_string = rtrim($fields_string,'&');
	//echo $fields_string.'<br/><br/>';
	
	$ch = curl_init("{$baseurl}?{$str}");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: OAuth realm=\"\", {$str}"));
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	$response = curl_exec($ch);
	curl_close($ch);
	
	//echo $response;
}

/* ./application/helpers/oauth_helper.php */
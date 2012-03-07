<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getGravatar($email, $size='32'){
	$hash = md5( strtolower( trim( $email ) ) );
	$avatar = 'http://www.gravatar.com/avatar/' . $hash . '?s=' . $size;
	return $avatar;
}
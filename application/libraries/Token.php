<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/libraries/JWT.php';
use \Firebase\JWT\JWT;

class Token
{
    public function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public function validateToken($token)
    {
        $CI =& get_instance();	
      	return JWT::decode($token, $CI->config->item('jwt_key'),array('HS256'));
    }

    public function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

}
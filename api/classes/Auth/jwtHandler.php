<?php 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHandler {
    protected $jwt_secret;
    protected $token;
    protected $issuedAt;
    protected $expire;
    protected $jwt;

    public function __construct() {
        date_default_timezone_set('Asia/Kolkata');
        $this->issuedAt = time();

        // Token Validity (3600 second = 1hr)
        $this->expire = $this->issuedAt + 86400;

        // Set your secret or signature
        $this->jwt_secret = "this_is_my_secrect";
    }

    public function jwtEncodeData($iss, $data) {
        $this->token = array(

            //Adding the identifier to the token (who issue the token)
            "iss" => $iss,

            "aud" => $iss,

            "iat" => $this->issuedAt,

            "exp" => $this->expire,

            "data" => $data
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secret, 'HS256');
        return $this->jwt;
    }

    public function jwtDecodeData($jwt_token)
    {
        try {
            $decode = JWT::decode($jwt_token, new Key($this->jwt_secret, 'HS256'));
            return [
                "status" => 200,
                "data" => $decode->data,
            ];
        } catch (Exception $e) {
            return [
                "status" => 300,
                "message" => $e->getMessage()
            ];
        }
    }
}
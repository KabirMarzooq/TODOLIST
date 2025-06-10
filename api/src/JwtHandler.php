<?php
use \Firebase\JWT\JWT;
class JwtHandler {

    private $secretkey = "4b1c037407b255283ae81bbe173a99898ddb8b8af0029c965cb8d18a3b1f48365e2acdeb7c8b633cb2ed5b742cf66e69f07472c5d9a010031528c59129187acddd37579cbea958178e1e0d42eb3ea86365957406426fb119fd40f9a479a74107113d2565faefab7e310fb27ab6efc00520832be6d34df386a6e4bba27175e55be88859c4acdec60070272b4233fe14b4267329f45ac7af544449bb1f41c5288dcb5f06902622338d9501e3bbdbf63e03e36cae8659f9aa0c4b5b79e75cbea973403cc4c1c258e904970430087ed0039cb8e8bdeefa204d4848c8185920aa5467c9ab470d64caf8436dd01200dc9bf1161c516ed35423687e597dc27847fbc93d";

    private $alg = "HS256";

    private $issuer = "http://localhost/TODOLIST/public/api/index.php";

    private $accessTokenExpiration = 3600; // 1 hour

    private $refreshTokenExpiration = 604800; // 7 Days

    function __construct(private \Firebase\JWT\JWT $jwt,){

    }

    public function generateAccessToken($userId): string{

        $issuedAt = time();
        $payload = [
            'iss' => $this->issuer,
            'iat' => $issuedAt,
            'exp' => $issuedAt +
            $this->accessTokenExpiration,
            'sub' => $userId,
        ];

        return $this->jwt::encode($payload, $this->secretkey,
        $this->alg);

    }

    public function generateRefreshToken($userId): string{

        $issuedAt = time();
        $payload = [
            'iss' => $this->issuer,
            'iat' => $issuedAt,
            'exp' => $issuedAt + $this->refreshTokenExpiration,
            'sub' => $userId,
        ];

        return $this->jwt::encode($payload, $this->secretkey, $this->alg);

    }

    public function verifyJWT($token): bool|array{
        try {
            $decode = $this->jwt::decode($token, new \Firebase\JWT\key($this->secretkey, $this->alg));//dependency injection
            return(array) $decode;
        }catch(Exception $e){
            return ['error' => 'Invalid token: '. $e->getMessage()];
        }
    }

    public function extractTokenFromHeader($header): null|string{

        $authHeader = $headers['Authorization']?? "";

        $matches = [];

        if(preg_match('/Bearer\s(\S+)/', $authHeader, $matches)){
            return $matches[1];
        }

        return null;
    }

}

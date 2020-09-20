<?php
include_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        if (
            !empty($data->role)
        ) {
            if ($data->role != '' && $data->role == 'admin' || $data->role == 'pemilik') {
                return role($data, $data->role);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Unable to role. Data is Fail."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to login. Data is incomplete."));
        }
        break;

    default:
        echo '{"status":"405","result": "Method Not Allowed"}';
        break;
}

function role($json, $role)
{
    include "../config/koneksi.php";
    if (
        !empty($json->email) &&
        !empty($json->password)
    ) {
        $email = $json->email;
        $password = $json->password;
        $query  = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);
        if($json->role == $data['role']){
            if ($data != false) {
                $password2 = $data['password'];
                if (password_verify($password, $password2)) {
                    $secret_key = "q99D4VUeCzYsb2cBZCoiC+QuYXlsqul8jekWag";
                    $issuer_claim = "CBC-256";
                    $audience_claim = "EXXQEEE";
                    $issuedat_claim = time();
                    $notbefore_claim = $issuedat_claim + 10;
                    $expire_claim = $issuedat_claim + 60;
                    $token = array(
                        "iss" => $issuer_claim,
                        "aud" => $audience_claim,
                        "iat" => $issuedat_claim,
                        "nbf" => $notbefore_claim,
                        "exp" => $expire_claim,
                        "data" => array(
                            'id' => $data['id'],
                            'nama' => $data['nama'],
                            'email' => $data['email'],
                            'password' => $data['password'],
                            'role' => $data['role']
                        )
                    );
                    http_response_code(200);
                    $jwt = JWT::encode($token, $secret_key);
                    echo json_encode(
                        array(
                            "status" => "200",
                            "message" => "Successful login.",
                            "token" => $jwt,
                            "email" => $email,
                            "expireAt" => $expire_claim,
                            "role" => $role
                        )
                    );
                } else {

                    http_response_code(400);
                    echo json_encode(array("message" => "Login failed.", "Password" => $password));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Login failed.", "Email" => $email));
            }
        }else{
            http_response_code(400);
            echo json_encode(array("message" => "Login failed.", "Role" => $role));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to login. Data is incomplete."));
    }
}

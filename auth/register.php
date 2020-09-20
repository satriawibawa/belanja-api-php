<?php


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
function role($data, $role)
{
  include "../config/koneksi.php";
  if (
    !empty($data->nama) &&
    !empty($data->email) &&
    !empty($data->password)
  ) {
    $nama = $data->nama;
    $email = $data->email;
    $password = $data->password;
    $password = password_hash($password, PASSWORD_BCRYPT);
    $user = cekEmail($email);
    if ($user != false) {
      echo json_encode(array("status" => "400", "message" => "User telah ada dengan email " . $email));
    } else {
      $user = simpan($nama, $email, $password, $role);
      if ($user != false) {
        // simpan user berhasil
      } else {
        // gagal menyimpan user
      }
    }
  } else {
    http_response_code(400);
    echo json_encode(array("status" => "400", "message" => "Data Kurang Lengkap(Invalid)"));
  }
}
function cekEmail($email)
{
  $conn = null;
  include "../config/koneksi.php";

  $query  = "SELECT email from users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if ($result->num_rows > 0) {
    // user telah ada 
    $result->close();
    return true;
  } else {
    // user belum ada 
    $result->close();
    return false;
  }
}
function simpan($nama, $email, $password, $role)
{
  include "../config/koneksi.php";
  $query = "INSERT INTO users ( nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')";
  $data = mysqli_query($conn, $query);
  if ($data != false) {
    http_response_code(200);
    echo json_encode(array("status" => "200", "message" => "User was successfully registered."));
    return true;
  } else {
    http_response_code(400);
    echo json_encode(array(
      "status" => "400", "message" => "Unable to register the user.",
    ));
    return false;
  }
}
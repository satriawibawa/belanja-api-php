<?php
 

  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: POST");

  $request=$_SERVER['REQUEST_METHOD'];

  switch ( $request) {
  case 'POST':
    $data=json_decode(file_get_contents('php://input'),true);
    postmethod($data);
  break;

  default:
    echo '{"status":"405","result": "Method Not Allowed"}';
    break;
  }
  //data insert part are here
  function postmethod($data){
      include "../config/koneksi.php";
      $nama=$data["nama"];
      $alamat=$data["alamat"];
      $no_hp=$data["no_hp"];
      $email=$data["email"];
      $username = $data['username'];
      $password = $data['password'];

      $sql= "INSERT INTO tb_admin ( nama_lengkap, alamat, no_hp, email, username, password) VALUES ('$nama', '$alamat', '$no_hp', '$email', '$username', '$password')";
      if (mysqli_query($conn , $sql)) {
          echo '{"status":"200","result": "data inserted"}';
      } else{
        echo '{"status":"400","result": "data not inserted"}';
    }
  }

  //data edit part are here
  function putmethod($data){
    include "../config/koneksi.php";
    $id_mhs=$data["id_mhs"];
    $name=$data["name"];
    $nim=$data["nim"];
    $dosen=$data["dosen"];
    $jurusan=$data["jurusan"];
    $matkul = $data['matkul'];
    $tugas = $data['tugas'];

    $sql= "UPDATE mahasiswa SET name='$name', nim='$nim', dosen='$dosen', jurusan='$jurusan' WHERE id_mhs='$id_mhs'";

    if (mysqli_query($conn , $sql)) {
        echo '{"result": "Update Succesfully"}';
    } else{

        echo '{"result": "not updated"}';
    }

  }
  //delete method are here,,,,,,,,,,,,,,
  function deletemethod($data){
    include "../config/koneksi.php";

    $id_mhs=$data["id_$id_mhs"];
    $sql= "DELETE FROM mahasiswa WHERE id_mhs=$id_mhs";

    if (mysqli_query($conn , $sql)) {
        echo '{"result": "delete Succesfully"}';
    } else{

        echo '{"result": "not deleted"}';
    }
  }
?>
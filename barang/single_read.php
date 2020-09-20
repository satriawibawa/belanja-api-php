<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../class/barang.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Barang($db);

    $item->id = isset($_GET['id']) ? $_GET['id'] : die();
  
    $item->getSingleBarang();
    
    if($item->nama != null){
        $brng_arr = array(
            "id" =>  $item->id,
            "name" => $item->nama,
            "jenis" => $item->jenis,
            "harga" => $item->harga
        );
      
        http_response_code(200);
        echo json_encode($brng_arr);
    }
      
    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>
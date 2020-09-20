<?php
 if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
    
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }

include_once '../config/database.php';
include_once '../class/barang.php';

$database = new Database();
$db = $database->getConnection();

$item = new Barang($db);

$data = json_decode(file_get_contents("php://input"));
if (
    !empty($data->nama) &&
    !empty($data->jenis) &&
    !empty($data->harga)
) {
    $item->nama = $data->nama;
    $item->jenis = $data->jenis;
    $item->harga = $data->harga;

    if ($item->createBarang()) {
        echo json_encode(array("message" => "Barang created successfully."));
    } else {
        echo json_encode(array("message" => "Barang could not be created."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}

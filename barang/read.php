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

    $items = new Barang($db);

    $stmt = $items->getBarang();
    $itemCount = $stmt->rowCount();

    if($itemCount > 0){
        
        $barangArr = array();
        $barangArr["body"] = array();
        $barangArr["itemCount"] = $itemCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "id" => $id_barang,
                "nama" => $nama_barang,
                "jenis" => $jenis_barang,
                "harga" => $harga_barang
            );

            array_push($barangArr["body"], $e);
        }
        echo json_encode($barangArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>

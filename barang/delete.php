<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../class/barang.php';

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        deletemethod($data);
        break;

    default:
        echo '{"status":"405","result": "Method Not Allowed"}';
        break;
}
function deletemethod($data){
    $database = new Database();
    $db = $database->getConnection();

    $item = new Barang($db);
    $item->id = isset($_GET['id']) ? $_GET['id'] : die();
    if (
        !empty($item->id)
    ) {
        // $item->id = $data->id;

        if ($item->deleteBarang()) {
            echo json_encode(array("message" => "Data deleted."));
        } else {
            echo json_encode(array("message" => "Data could not be deleted"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to delete product. Data is incomplete."));
    }
}
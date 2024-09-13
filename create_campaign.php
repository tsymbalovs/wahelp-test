<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'Database.php';
include_once 'Campaign.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

$campaign = new Campaign($db);

$campaign->title = $data->title;
$campaign->message = $data->message;

if ($campaign->create()) {
    echo json_encode(["message" => "Рассылка создана успешно!", "campaign_id" => $campaign->id]);
} else {
    echo json_encode(["message" => "Не удалось создать рассылку."]);
}

<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'Database.php';
include_once 'Campaign.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$campaign_id = isset($_GET['campaign_id']) ? intval($_GET['campaign_id']) : die(json_encode(["message" => "Не передан ID рассылки"]));

$campaign = new Campaign($db);
$campaign->id = $campaign_id;

try {
    $users = $campaign->getUsersForResend();

    $count = 0;

    while ($row = $users->fetch(PDO::FETCH_ASSOC)) {
        $campaign->markUserAsSent(user_id: $row['id']);

        $count++;
    }

    echo json_encode(["message" => "Сообщения отправлены $count пользователям"]);

} catch (Exception $e) {
    echo json_encode(["message" => "Произошла ошибка: " . $e->getMessage()]);

}

<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
        $file = fopen($_FILES['csv_file']['tmp_name'], 'r');

        $user = new User($db);

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            $user->phone_number = trim($data[0]);
            $user->name = trim($data[1]);

            if (!$user->create()) {
                echo json_encode(["message" => "Ошибка при добавлении пользователя."]);
                exit();
            }
        }

        fclose($file);
        echo json_encode(["message" => "Пользователи успешно загружены."]);
    } else {
        echo json_encode(["message" => "Ошибка загрузки файла."]);
    }
} else {
    echo json_encode(["message" => "Метод не поддерживается."]);
}

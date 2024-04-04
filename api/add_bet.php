<?php
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    $event_name = $data->event_name;
    $event_name2 = $data->event_name2;
    $bet_type = $data->bet_type;
    $token = $data->token ?? "";

    $user->setToken($token);
    $result = $user->addBet($event_name, $event_name2, $bet_type);

    echo json_encode(['success' => $result]);
}
?>

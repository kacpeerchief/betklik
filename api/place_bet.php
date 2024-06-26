<?php
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $betId = $data['betId'];
    $result = $data['result'];
    $betAmount = $data['betAmount'];
    $eventName = $data['event_name'];
    $eventName2 = $data['event_name2'];
    $token = $data["token"];

    $user->setToken($token);
    $success = $user->placeBet($betId, $result, $betAmount, $eventName, $eventName2);

    if ($success) {
        $userId = $user->getUserIdFromToken($token);

        $user->updateUserBalance($userId, -$betAmount);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nie możesz obstawić drugi raz tego samego zakładu.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

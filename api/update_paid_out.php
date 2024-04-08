<?php
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $betId = $data['betId'];
    $userId = $user->getUserIdFromToken($data['token']);
    $paidOutStatus = true;

    if ($userId !== null) {
        $success = $user->updatePaidOutStatus($betId, $userId, $paidOutStatus);
        echo json_encode(["success" => $success]);
    } else {
        echo json_encode(["success" => false, "message" => "Nie można znaleźć użytkownika."]);
    }
}
?>

<?php
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $betId = $data['betId'];
    $paidOutStatus = true;

    $success = $user->updatePaidOutStatus($betId, $paidOutStatus);

    echo json_encode(["success" => $success]);
}

?>

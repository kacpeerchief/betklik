<?php
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $betId = $data['betId'];
    $paidOutStatus = true; // Określamy stan wypłaty jako true

    $success = $user->updatePaidOutStatus($betId, $paidOutStatus); // Przekazujemy drugi argument

    echo json_encode(["success" => $success]);
}

?>

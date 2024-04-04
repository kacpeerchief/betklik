<?php
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    $balance = $data["balance"];
    $token = $data["token"] ?? ""; 
    
    if ($balance >= 0) {
        $user->setToken($token);
        $success = $user->updateProfile($balance);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Wprowadzona kwota nie może być ujemna']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

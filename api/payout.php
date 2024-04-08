<?php
require_once 'User.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['token']) && isset($data['amount'])) {
        $userId = $user->getUserIdFromToken($data['token']);
        
        if ($userId !== null) {
            $amount = $data['amount'];
            $success = $user->payout($userId, $amount);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Nie udało się wypłacić środków.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Nie można znaleźć użytkownika.']);
        }
    } else {
        echo json_encode(['error' => 'Brak wymaganych danych.']);
    }
} else {
    echo json_encode(['error' => 'Żądanie musi być wykonane metodą POST.']);
}
?>

<?php
require_once 'User.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['token'])) {
        $token = $data['token'];
        $user->setToken($token);

        $userBets = $user->getUserBets();

        if (!empty($userBets)) {
            echo json_encode($userBets);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode(['error' => 'Token użytkownika nie został przesłany.']);
    }
} else {
    echo json_encode(['error' => 'Żądanie musi być wykonane metodą POST.']);
}
?>

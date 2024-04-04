<?php
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['token'])) {
        $token = $data['token'];
        $betId = $data['betId'];
        $result = $data['result'];

        // Sprawdzenie, czy zakład został już zakończony
        if ($user->isBetEnded($betId)) {
            $response = ["success" => false, "message" => "Ten zakład został już zakończony."];
        } else {
            $response = $user->endBet($betId, $result, $token);
        }
    } else {
        $response = ["success" => false, "message" => "Brak tokena"];
    }
} else {
    $response = ["success" => false, "message" => "Metoda żądania nieprawidłowa"];
}

// Zwróć odpowiedź jako JSON
header("Content-Type: application/json");
echo json_encode($response);
?>

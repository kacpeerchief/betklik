<?php
require_once 'User.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['betId'])) {
        $betId = $data['betId'];
        
        $result = $user->getBetResult($betId);

        if ($result !== false) {
            echo json_encode(['success' => true, 'result' => $result]);
        } else {
            echo json_encode(['success' => true, 'result' => null]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Nie przesłano identyfikatora zakładu.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Żądanie musi być wykonane metodą POST.']);
}
?>

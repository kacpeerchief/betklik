<?php 
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $daneWejsciowe = json_decode(file_get_contents("php://input"), true);

    $username = $daneWejsciowe['username'];
    $password = $daneWejsciowe['password'];
    // $is_admin = isset($daneWejsciowe['is_admin']) ? 1 : 0;
    $is_admin = 0;
    
    $isSuccess = $user->register($username, $password, $is_admin);

    if ($isSuccess) {
        $daneWyjsciowe = ["success" => true, "message" => "Pomyślnie zarejestrowano nowego użytkownika", "token" => $user->getToken()];
        echo json_encode($daneWyjsciowe);
    } else {
        $daneWyjsciowe = ["success" => false, "message" => "Błąd podczas rejestrowania użytkownika"];
        echo json_encode($daneWyjsciowe);
    }
}
?>


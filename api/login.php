<?php 
require_once 'User.php';

$user = new User();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $daneWejsciowe = json_decode(file_get_contents("php://input"), true);

    $username = $daneWejsciowe['username'];
    $password = $daneWejsciowe['password'];

    $isSuccess = $user->login($username, $password);

    if ($isSuccess) {
        $daneWyjsciowe = ["success" => true, "message" => "Pomyślnie zalogowano", "token" => $user->getToken()];
        echo json_encode($daneWyjsciowe);
    } else {
        $daneWyjsciowe = ["success" => false, "message" => "Błąd podczas logowania użytkownika"];
        echo json_encode($daneWyjsciowe);
    }
}
?>


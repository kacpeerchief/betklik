<?php 
require_once 'User.php';

$user = new User();

$daneWejsciowe = json_decode(file_get_contents("php://input"), true);
$token = $daneWejsciowe['token'] ?? "";

$user->setToken($token);
$user->logout();

echo json_encode(["success" => true])
?>

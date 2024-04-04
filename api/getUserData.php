<?php 
require_once 'User.php';

$user = new User();

$daneWejsciowe = json_decode(file_get_contents("php://input"), true);
$token = $daneWejsciowe['token'] ?? "";

$user->setToken($token);

$username = $user->getUsername();
$balance = $user->getBalance();
$isAdmin = $user->isAdmin();
echo json_encode(["username" => $username, "balance" => $balance, "isAdmin" => $isAdmin]);
?>

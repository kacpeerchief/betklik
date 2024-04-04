<?php
require_once 'User.php';

$user = new User();
$allBets = $user->getAllBets();

echo json_encode($allBets);
?>

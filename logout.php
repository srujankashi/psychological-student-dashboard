<?php
include '../functions.php';
unset($_SESSION['user_id']);
unset($_SESSION['username']);

session_destroy();


header('Location: index.php');
?>
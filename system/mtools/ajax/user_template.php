<?php
include_once("../../../core/inc/core.php");
$Core = new Core;
$Hash = new PasswordHash(8, TRUE);

$db = $Core->db();

$username = $_POST['username'];
$username_clean = strtolower($username);
$password = $Hash->HashPassword($_POST['password']);

$params = array($username, $username_clean, $password);
$Main = $Core->pdoQuery($db, 'INSERT INTO core_users (username, username_clean, password) VALUES (?,?,?)', $params);
?>
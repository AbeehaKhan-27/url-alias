<?php

$server = "localhost";
$username = "root";
$pass = "";
$database = "friendly_url";

$conn = mysqli_connect($server, $username, $pass, $database, 3306);

if(!$conn) {
    die("Connection failed.". mysqli_connect_error());
}
?>
<?php
//Thong tin ket noi MySQL
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'atntoy';


//ket noi MySQL
$conn = mysqli_connect($hostname, $username, $password, $database);

//kiem tra ket noi MySQL
if (!$conn){
    die('Error connect to MySQL: ' .mysqli_connect_error());
}

//echo 'ket noi thanh cong den MySQL';
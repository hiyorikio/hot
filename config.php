<?php
$conn = mysqli_connect('MySQL-8.4', 'root', '', 'VAL_db');

if(!$conn){
    die("Ошибка подключения: " . mysql_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
<?php

  $servername = "localhost";
  $database = "pphp";
  $username = "root";
  $password = "";

  $conn = mysqli_connect($servername,$username, $password, $database);

if(!$conn){
    die("Falha na conexão: ".mysqli_connect_error());
}
?>
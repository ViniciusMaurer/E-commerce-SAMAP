<?php
    $localhost = "localhost";
    $user = "root";
    $password = "";
    $banco = "samap";

    $conn = mysqli_connect($localhost, $user, $password, $banco);

    if(!$conn){
        header("Location: index.php");
    }
?>
<?php
session_start();

if (isset($_SESSION["usuario"]) || isset($_SESSION["senha"])) {
    unset($_SESSION["usuario"]);
    unset($_SESSION["senha"]);
    
    session_destroy();
    
    header("Location: index.php");
    exit(); 
} else {
    header("Location: index.php");
    exit();
}
?>
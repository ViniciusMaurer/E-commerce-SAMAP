<?php
    include_once "conexao.php";

    if (!$conn) {
		echo  "<script>alert('Nao foi possivel conectar ao banco de dados!');</script>";
		header('Location: logout.php');
	}			

	session_start();
	if ((!isset($_SESSION["usuario"])) || (!isset($_SESSION["senha"]))) {
		header("Location: index.html");
	}

    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

    $sql = "SELECT ID FROM USUARIO WHERE EMAIL = '$usuario' AND SENHA = '$senha'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $usuario_id = $row['ID'];

    $endereco_id = $_GET['id_excluir'];
    $pagina = $_GET['pagina'];

    $sql = "DELETE FROM GRUPO_ENDERECO_USUARIO WHERE ID_USUARIO = $usuario_id AND ID_ENDERECO = $endereco_id";
    $result = mysqli_query($conn, $sql);

    $sql = "DELETE FROM ENDERECO WHERE ID = $endereco_id";
    $result = mysqli_query($conn, $sql);

    if($result){
        if($pagina == 'carrinho_entrega'){
            header("Location: carrinho_entrega.php");
        }
        if($pagina == 'perfil'){
            header("Location: perfil.php");
        }
    }
    else{
        header("Location: index.html");
    }
?>
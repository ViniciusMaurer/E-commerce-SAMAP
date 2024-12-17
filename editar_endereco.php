<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/icone-samap.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/card.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Samap</title>
</head>
<body>
    <header>
        <div class="container-fluid bg-white fixed-top">
            <div class="container">
                <div class="row">
                    <nav class="navbar navbar-expand-lg navbar-light navbar-style">
                        <a class="navbar-brand" href="inicio.php"><img class="img-fluid" src="img/logo-samap.png" alt="Logo Samap"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <a class="nav-link" id="navcolor" href="inicio.php">INÍCIO</a>
                                <a class="nav-link" id="navcolor" href="ofertas.php">OFERTAS</a>
                                <a class="nav-link" id="navcolor" href="contato.php">SUPORTE</a>
                                <a class="nav-link" id="navcolor" href="carrinho.php">
                                    <img class="img-fluid" src="img/icone-carrinho.png" alt="Carrinho">
                                </a>
                                <?php
                                session_start(); 
                                if(isset($_SESSION['usuario']) && $_SESSION['usuario'] != null) { ?>
                                    <div class="dropdown">
                                        <a class="nav-link" id="navcolor" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img class="img-fluid" src="img/icone-conta.png" alt="Conta">
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-perfil" aria-labelledby="dropdownMenuLink">
                                            <li class="dropdown-item-perfil"><a class="dropdown-item dropdown-item-perfil" href="perfil.php">Perfil</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li class="dropdown-item-perfil"><a class="dropdown-item dropdown-item-perfil" href="logout.php">Sair</a></li>
                                        </ul>
                                    </div>
                                <?php } 
                                else{ ?>
                                    <a class="nav-link" id="navcolor" href="perfil.php">
                                        <img class="img-fluid" src="img/icone-conta.png" alt="Perfil">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <?php 
    include_once "conexao.php";

    if (!$conn) {
		echo  "<script>alert('Nao foi possivel conectar ao banco de dados!');</script>";
		header('Location: logout.php');
	}			

	if ((!isset($_SESSION["usuario"])) || (!isset($_SESSION["senha"]))) {
		header("Location: index.html");
	}

    if (isset($_POST['paginaSelecionada']) && isset($_POST['idEndereco'])) {
        $enderecoId = intval($_POST['idEndereco']);
        $pagina = $_POST['paginaSelecionada'];
    
        $sql = "SELECT E.*, ES.NOME AS ESTADO FROM ENDERECO E, ESTADO ES WHERE E.ID = $enderecoId AND ES.ID = E.ID_ESTADO";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $endereco = mysqli_fetch_assoc($result);

            $rua = $endereco['RUA'];
            $numero = $endereco['NUMERO'];
            $bairro = $endereco['BAIRRO'];
            $complemento = $endereco['COMPLEMENTO'];
            $cidade = $endereco['CIDADE'];
            $cep = $endereco['CEP'];
            $estado_id = $endereco['ID_ESTADO'];
            $estado = $endereco['ESTADO'];
        } else {
            echo "Endereço não encontrado.";
        }
    } else {
        echo "Erro: Nenhum endereço foi selecionado.";
    }

    if (isset($_POST['name_id']) && isset($_POST['name_rua']) && isset($_POST['name_numero']) && isset($_POST['name_bairro']) && isset($_POST['name_complemento']) &&
    isset($_POST['name_cidade']) && isset($_POST['name_cep']) && isset($_POST['name_idestado']) && isset($_POST['name_pagina'])) {
        $enderecoId = $_POST['name_id'];
        $rua = $_POST['name_rua'];
        $numero = $_POST['name_numero'];
        $bairro = $_POST['name_bairro'];
        $complemento = $_POST['name_complemento'];
        $cidade = $_POST['name_cidade'];
        $cep = $_POST['name_cep'];
        $estado_id = $_POST['name_idestado'];
        $pagina = $_POST['name_pagina'];

        $sqlUpdate = "UPDATE ENDERECO 
                         SET RUA = '$rua', NUMERO = '$numero', BAIRRO = '$bairro', COMPLEMENTO = '$complemento', CIDADE = '$cidade', CEP = '$cep', ID_ESTADO = '$estado_id'
                       WHERE ID = $enderecoId ";

        $result = mysqli_query($conn, $sqlUpdate);

        $sql = "SELECT NOME FROM ESTADO WHERE ID = '$estado_id'";
        $result = mysqli_query($conn, $sql);

        $row = mysqli_fetch_assoc($result);
        $estado = $row['NOME'];
    }
    ?>

    <div class="container-fluid" style="margin-top: 6.25rem; background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: repeat; height: auto; background-attachment: fixed; min-height: 100vh;">
        <div class="container d-flex justify-content-center align-items-center min-vh-100" style="padding: 10px; border-radius: 30px;">
            <div class="bg-light col-12 col-md-6" style="padding: 30px; border-radius: 30px;">
                <div class="row pt-3 pt-md-0">
                    <div class="col-12" style="padding: 10px;">
                        <h2 class="titulo-carrinho"><span> EDITAR ENDEREÇO:</span>
                            <span style="float: right;">
                                <?php 
                                if($pagina == 'carrinho_entrega') { ?>
                                    <a href="carrinho_entrega.php"><img class="img-fluid" style="max-width: 24px;" src="img/fechar-x.png" alt=""></a>
                                <?php }
                                if($pagina == 'perfil') { ?>
                                    <a href="perfil.php"><img class="img-fluid" style="max-width: 24px;" src="img/fechar-x.png" alt=""></a>
                                <?php } ?>
                            </span>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 p-3 p-md-2">
                        <form action="editar_endereco.php" method="POST">
                            <input type="hidden" name="name_id" value="<?php echo $enderecoId; ?>">
    
                            <h5 class="pt-3">Rua</h5>
                            <input type="text" class="input-perfil col-12" name="name_rua" autocomplete="off" value="<?php echo $rua; ?>" required>
    
                            <h5 class="pt-3">Número</h5>
                            <input type="text" class="input-perfil col-12" name="name_numero" autocomplete="off" value="<?php echo $numero; ?>" required>
    
                            <h5 class="pt-3">Bairro</h5>
                            <input type="text" class="input-perfil col-12" name="name_bairro" autocomplete="off" value="<?php echo $bairro; ?>" required>
                            
                            <h5 class="pt-3">Complemento</h5>
                            <input type="text" class="input-perfil col-12" name="name_complemento" autocomplete="off" value="<?php echo $complemento; ?>" required>
                            
                            <h5 class="pt-3">Cidade</h5>
                            <input type="text" class="input-perfil col-12" name="name_cidade" autocomplete="off" value="<?php echo $cidade; ?>" required>
    
                            <h5 class="pt-3">CEP</h5>
                            <input type="text" class="input-perfil col-12" name="name_cep" autocomplete="off" value="<?php echo $cep; ?>" required>

                            <h5 class="pt-3">Estado</h5>
                            <select class="form-control" name="name_idestado" required>
                                <option value="<?php echo $estado_id;?>" selected><?php echo $estado; ?></option>
                                <?php 
                                $sql1 = "SELECT ID, NOME FROM ESTADO ORDER BY NOME";
                                $selectestado = mysqli_query($conn, $sql1);
                                while ($linha1 = mysqli_fetch_array($selectestado, MYSQLI_NUM)) { ?>
                                    <option value="<?php echo $linha1[0]; ?>"><?php echo $linha1[1]; ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="name_pagina" autocomplete="off" value="<?php echo $pagina; ?>">
                            <br><br>
                            <div class="text-end">
                                <button class="btn btn-outline-success" type="submit">Salvar Endereço</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php mysqli_close($conn); ?>

    <div class="container-fluid">
        <div class="row">
            <div class="footer bg-dark py-3">
                <center>
                    <p style="color: white">Todos os direitos reservados - Samap®</p>
                </center>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/icone-samap.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/card.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Adicionar Endereço</title>
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

    // Verifica a conexão
    if (!$conn) {
        echo "<script>alert('Não foi possível conectar ao banco de dados!');</script>";
        header('Location: logout.php');
    }			

    if (!isset($_SESSION["usuario"]) || !isset($_SESSION["senha"])) {
        header("Location: index.html");
    }

    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

    $sql = "SELECT ID FROM USUARIO WHERE EMAIL = '$usuario' AND SENHA = '$senha'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $usuario_id = $row['ID'];

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $pagina = $_GET['pagina'];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['rua'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'], 
                  $_POST['cidade'], $_POST['cep'], $_POST['estado_id'], $_POST['pagina'])) {
            
            $rua = $_POST['rua'];
            $numero = $_POST['numero'];
            $bairro = $_POST['bairro'];
            $complemento = $_POST['complemento'];
            $cidade = $_POST['cidade'];
            $cep = $_POST['cep'];
            $estado_id = $_POST['estado_id'];
            $pagina = $_POST['pagina'];

            // SQL para inserir novo endereço
            $sqlInsert = "INSERT INTO ENDERECO (RUA, NUMERO, BAIRRO, COMPLEMENTO, CIDADE, CEP, ID_ESTADO) 
            VALUES ('$rua', '$numero', '$bairro', '$complemento', '$cidade', '$cep', '$estado_id')";

            if (mysqli_query($conn, $sqlInsert)) {
                // Obter o ID do registro inserido
                $endereco_id = mysqli_insert_id($conn);
            } else {
                echo "<script>alert('Erro ao adicionar endereço: " . mysqli_error($conn) . "');</script>";
            }

            // SQL para inserir novo endereço
            $sqlInsert = "INSERT INTO GRUPO_ENDERECO_USUARIO (ID_USUARIO, ID_ENDERECO) 
                          VALUES ('$usuario_id', '$endereco_id')";
            
            if (mysqli_query($conn, $sqlInsert)) {
                if($pagina == "perfil"){
                    header('Location: perfil.php');
                }else{
                    header('Location: carrinho_entrega.php');
                }
            } else {
                echo "<script>alert('Erro ao adicionar endereço: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
    ?>

    <div class="container-fluid" style="margin-top: 6.25rem; background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: repeat; height: auto; background-attachment: fixed; min-height: 100vh;">
        <div class="container pb-3 d-flex justify-content-center align-items-center min-vh-100">
            <div class="bg-light col-12 col-md-6" style="padding: 10px; border-radius: 30px;">
                <div class="row">
                    <div class="col-12">
                        <h2 class="titulo-carrinho"><span>ADICIONAR ENDEREÇO:</span><span style="float: right;">
                        <?php 
                        if($pagina == "carrinho"){ ?>
                            <a href="carrinho_entrega.php"><img class="img-fluid" style="max-width: 24px;" src="img/fechar-x.png" alt=""></a></span></h2>
                        <?php 
                        } 
                        else{ ?>
                            <a href="perfil.php"><img class="img-fluid" style="max-width: 24px;" src="img/fechar-x.png" alt=""></a></span></h2>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 p-3 p-md-5">
                        <form action="adicionar_novo_endereco.php" method="POST">
                            <h5 class="pt-3">Rua</h5>
                            <input type="text" class="input-perfil col-12" name="rua" autocomplete="off" required>

                            <h5 class="pt-3">Número</h5>
                            <input type="text" class="input-perfil col-12" name="numero" autocomplete="off" required>

                            <h5 class="pt-3">Bairro</h5>
                            <input type="text" class="input-perfil col-12" name="bairro" autocomplete="off" required>
                            
                            <h5 class="pt-3">Complemento</h5>
                            <input type="text" class="input-perfil col-12" name="complemento" autocomplete="off">

                            <h5 class="pt-3">Cidade</h5>
                            <input type="text" class="input-perfil col-12" name="cidade" autocomplete="off" required>

                            <h5 class="pt-3">CEP</h5>
                            <input type="text" class="input-perfil col-12" name="cep" autocomplete="off" required>

                            <h5 class="pt-3">Estado</h5>
                            <select class="form-control" name="estado_id" required>
                                <option value="" disabled selected>Selecione um estado</option>
                                <?php 
                                // Carrega Estados do banco de dados
                                $sql1 = "SELECT ID, NOME FROM ESTADO ORDER BY NOME";
                                $selectestado = mysqli_query($conn, $sql1);
                                while ($linha1 = mysqli_fetch_array($selectestado, MYSQLI_NUM)) { ?>
                                    <option value="<?php echo $linha1[0]; ?>"><?php echo $linha1[1]; ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="pagina" value="<?php echo $pagina; ?>">
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
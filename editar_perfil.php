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
  
    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

    $sql = "SELECT ID FROM USUARIO WHERE EMAIL = '$usuario' AND SENHA = '$senha'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $usuario_id = $row['ID'];

    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = isset($_POST['email']) && !empty(trim($_POST['email'])) ? trim($_POST['email']) : null;
    $verifica_vendedor = isset($_POST['verifica_vendedor']) && !empty(trim($_POST['verifica_vendedor'])) ? trim($_POST['verifica_vendedor']) : null;
    $telefone = isset($_POST['telefone']) && !empty(trim($_POST['telefone'])) ? trim($_POST['telefone']) : null;
    $nascimento = isset($_POST['nascimento']) && !empty(trim($_POST['nascimento'])) ? trim($_POST['nascimento']) : null;
    $nascimento = $nascimento != null ? converterData($nascimento) : null;
    $cpf = isset($_POST['cpf']) && !empty(trim($_POST['cpf'])) ? trim($_POST['cpf']) : null;

    function converterData($data) {
        // Verifica se a data está no formato DD/MM/YYYY
        if (preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $data, $matches)) {
            return "{$matches[3]}-{$matches[2]}-{$matches[1]}"; // Retorna a data no formato YYYY-MM-DD
        }
        return $data; 
    }

    if(isset($_POST['salvar_perfil'])){
        if(!empty($nascimento)){
            $nascimentoFormatado = converterData($nascimento);
        }
        else{
            $nascimentoFormatado = null;
        }

        $_SESSION['usuario'] = $email;

        $sqlUpdate = "UPDATE USUARIO 
                         SET NOME = " . (!empty($nome) ? "'$nome'" : 'null') . ",
                             SOBRENOME = " . (!empty($sobrenome) ? "'$sobrenome'" : 'null') . ",
                             DATA_NASC = " . (!empty($nascimentoFormatado) ? "'$nascimentoFormatado'" : 'null') . ",
                             CPF = " . (!empty($cpf) ? "'$cpf'" : 'null') . ",
                             EMAIL = " . (!empty($email) ? "'$email'" : 'null') . ",
                             TELEFONE = " . (!empty($telefone) ? "'$telefone'" : 'null') . "
                       WHERE ID = '$usuario_id'";
        $result = mysqli_query($conn, $sqlUpdate);
    }
    ?>

    <div class="container-fluid" style="margin-top: 6.25rem; background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: repeat; height: auto; background-attachment: fixed; min-height: 100vh;">
        <div class="container d-flex justify-content-center align-items-center min-vh-100" style="padding: 10px; border-radius: 30px;">
            <div class="bg-light col-12 col-md-6" style="padding: 30px; border-radius: 30px;">
                <div class="row pt-3 pt-md-0">
                    <div class="col-12" style="padding: 10px;">
                        <h2 class="titulo-carrinho"><span> EDITAR PERFIL:</span>
                            <span style="float: right;">
                                <a href="perfil.php"><img class="img-fluid" style="max-width: 24px;" src="img/fechar-x.png" alt=""></a>
                            </span>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 p-3 p-md-2">
                        <form action="editar_perfil.php" method="POST">
                            <h5 class="pt-3">
                            <?php
                            if($verifica_vendedor == 1){
                                echo "Razão Social";
                            }else{
                                echo "Nome";
                            }
                            ?>
                            </h5>
                            <input type="text" class="input-perfil col-12" name="nome" autocomplete="off" value="<?php echo $nome; ?>">
                            
                            <h5 class="pt-3">
                            <?php
                            if($verifica_vendedor == 1){
                                echo "Nome Fantasia";
                            }else{
                                echo "Sobrenome";
                            }
                            ?>
                            </h5>
                            <input type="text" class="input-perfil col-12" name="sobrenome" autocomplete="off" value="<?php echo $sobrenome; ?>">

                            <h5 class="pt-3">Email</h5>
                            <input type="text" class="input-perfil col-12" name="email" autocomplete="off" value="<?php echo $email; ?>">
    
                            <h5 class="pt-3">Telefone</h5>
                            <input type="text" class="input-perfil col-12" name="telefone" autocomplete="off" value="<?php echo $telefone; ?>">
                            
                            <h5 class="pt-3">
                            <?php
                            if($verifica_vendedor == 1){
                                echo "Data de Fundação";
                            }else{
                                echo "Data de Nascimento";
                            }
                            ?>
                            </h5>
                            <input type="date" class="input-perfil col-12" name="nascimento" autocomplete="off" value="<?php echo $nascimento; ?>">
                            <h5 class="pt-3">
                            <?php
                            if($verifica_vendedor == 1){
                                echo "CNPJ";
                            }else{
                                echo "CPF";
                            }
                            ?>
                            </h5>
                            <input type="text" class="input-perfil col-12" name="cpf" autocomplete="off" value="<?php echo $cpf; ?>">
                            <input type="hidden" name="verifica_vendedor" value="<?php echo $verifica_vendedor; ?>">
                            <br><br>
                            <div class="text-end">
                                <button class="btn btn-outline-success" name="salvar_perfil" type="submit">Salvar Perfil</button>
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
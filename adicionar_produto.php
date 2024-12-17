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

        // Verifica a conexão
        if (!$conn) {
            echo "<script>alert('Não foi possível conectar ao banco de dados!');</script>";
            header('Location: logout.php');
        }			

        if (!isset($_SESSION["usuario"]) || !isset($_SESSION["senha"])) {
            header("Location: index.php");
        }
                            
        $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
        $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

        $sql = "SELECT NOME, SOBRENOME, VERIFICA_VENDEDOR, ID FROM USUARIO WHERE EMAIL = '$usuario' AND SENHA = '$senha'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $razaosocial = $row['NOME'];
        $fantasia = $row['SOBRENOME'];
        $verifica_vendedor = $row['VERIFICA_VENDEDOR'];
        $id_vendedor = $row['ID'];

        if($verifica_vendedor != "1"){
            mysqli_close($conn);
            header("Location: index.php");
            exit();
        }
    ?>

    <div class="container-fluid" style="margin-top: 6.25rem; background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: repeat; height: auto; background-attachment: fixed; min-height: 100vh;">
        <div class="container d-flex justify-content-center align-items-center min-vh-100" style="padding: 10px; border-radius: 30px;">
            <div class="bg-light col-12 col-md-6" style="padding: 10px; border-radius: 30px;">
                <div class="row pt-3 pt-md-0" >
                    <div class="col-12">
                        <h2 class="titulo-carrinho"><span>ADICIONAR PRODUTO:</span><span style="float: right;"><a href="inicio.php"><img class="img-fluid" style="max-width: 24px;" src="img/fechar-x.png" alt=""></a></span></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form action="cadastrar_produto.php" method="POST" enctype="multipart/form-data">

                            <h5 class="pt-3">Código:</h5>
                            <input type="number" class="input-perfil col-12" name="codigo" id="codigo" autocomplete="off" required>

                            <h5 class="pt-3">Nome:</h5>
                            <input type="text" class="input-perfil col-12" name="nome" id="nome" autocomplete="off" required>

                            <h5 class="pt-3">Descrição:</h5>
                            <input type="text" class="input-perfil col-12" name="descricao" id="descricao" autocomplete="off">

                            <h5 class="pt-3">Preço:</h5>
                            <input type="text" class="input-perfil col-12" name="preco" id="preco" autocomplete="off" required>

                            <h5 class="pt-3">Imagem:</h5>
                            <input type="file" class="input-perfil col-12" name="img" id="img" autocomplete="off">

                            <input type="hidden" name="img_caminho" id="img_caminho">

                            <h5 class="pt-3">Preço de Oferta:</h5>
                            <input type="text" class="input-perfil col-12" name="preco_oferta" id="preco_oferta" autocomplete="off">

                            <input type="hidden" name="id_loja" value="<?php echo $id_vendedor; ?>">

                            <br><br>
                            <input type="submit" value="Cadastrar">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("img").addEventListener("change", function() {
            if (this.files.length > 0) { 
                var fileName = this.files[0].name;
                document.getElementById("img_caminho").value = fileName;
            }
        });
    </script>

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
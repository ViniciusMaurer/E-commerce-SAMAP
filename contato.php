<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/icone-samap.png">
    <link rel="stylesheet" href="css/style.css">
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
                        <a class="navbar-brand" href="inicio.php">
                            <img class="img-fluid" src="img/logo-samap.png" alt="">
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <a class="nav-link" id="navcolor" href="inicio.php">INÍCIO</a>
                                <a class="nav-link" id="navcolor" href="ofertas.php">OFERTAS</a>
                                <a class="nav-link" id="navcolor" href="contato.php">
                                    <text class="selected">SUPORTE</text>
                                </a>
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

        $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
        $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

        $sql = "SELECT NOME, SOBRENOME, VERIFICA_VENDEDOR FROM USUARIO WHERE EMAIL = '$usuario' AND SENHA = '$senha'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $nome = $row['NOME'];
        $sobrenome = $row['SOBRENOME'];
        $verifica_vendedor = $row['VERIFICA_VENDEDOR'];
    ?>

    <!-- Conteúdo da página -->
    <div class="container-fluid pb-5" style="margin-top: 100px; background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container pb-5">
            <div class="row">
                <div class="col-12 pt-3">
                    <h2 class="titulo-contato">Como podemos ajudar?</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h2 class="titulo-contato" style="color: #3BBE51; font-style: italic;"><?php echo $verifica_vendedor != 1 ? $nome . " " : ""; echo $sobrenome; ?></h2>
                </div>
            </div>
        </div>

        <!-- Informações de contato da Samap -->
        <div class="container bg-dark" style="border-radius: 40px;">
            <div class="row" style="padding-top: 50px;">
                <div class="col-12 col-md-6" style="padding-left: 50px;">
                    <div class="row bt-5" style="padding-bottom: 40px;">
                        <div class="col-12 col-md-6">
                            <img src="img/localizaçao.svg" alt="" style="padding-bottom: 20px;">
                            <h1 style="color: #ffffff;">Endereço</h1>
                            <p style="color: #ffffff; font-size: 17px;">
                                R. Pastor Ernesto Schlieper,<br>
                                200 - Sete de Setembro, Ivoti<br>
                                RS, 93900-000
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <img src="img/email.svg" alt="" style="padding-bottom: 20px;">
                            <h1 style="color: #ffffff;">Email</h1>
                            <p style="color: #ffffff; font-size: 17px;">
                                samap@gmail.com<br>
                                supportesamap@gmail.com
                            </p>
                        </div>
                    </div>
                    <div class="row bt-5">
                        <div class="col-12 col-md-6">
                            <img src="img/telefone.svg" alt="" style="padding-bottom: 15px;">
                            <h1 style="color: #ffffff;">Telefone</h1>
                            <p style="color: #ffffff; margin-bottom: 30px; font-size: 17px;">55 51 982324918</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <h1 style="color: #ffffff; padding-top: 93px;">Redes</h1>
                            <img src="img/face.svg" style="height: 20px; width: auto; margin-bottom: 10px;" alt="">
                            <img src="img/x.svg" style="height: 20px; width: auto; padding-left: 15px; margin-bottom: 10px;" alt="">
                            <img src="img/insta.svg" style="height: 20px; width: auto; padding-left: 15px; margin-bottom: 10px;" alt="">
                        </div>
                    </div>
                </div>

                <!-- Google Maps -->
                <div class="col-12 col-md-6 pb-5 px-5">
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3468.8610006714844!2d-51.16389532542515!3d-29.607726711160876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9519453f0ed7c1f1%3A0xc85cbcfcd6417b48!2sInstituto%20Ivoti!5e0!3m2!1spt-BR!2sbr!4v1719249828667!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="footer bg-dark py-3">
                <center>
                    <p style="color: white;">Todos os direitos reservados - Samap®</p>
                </center>
            </div>
        </div>
    </div>
</body>
</html>
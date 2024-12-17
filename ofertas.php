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
                        <a class="navbar-brand" href="inicio.php"><img class="img-fluid" src="img/logo-samap.png" alt=""></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <a class="nav-link" id="navcolor" href="inicio.php">INÍCIO</a>
                                <a class="nav-link" id="navcolor" href="ofertas.php"><text class="selected">OFERTAS</text></a>
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

    $sql = "SELECT * FROM PRODUTO WHERE VERIFICA_OFERTA = 1";
    $result = mysqli_query($conn, $sql);
    ?>

    <div class="container-fluid pb-2" style="background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container-fluid container-md" style="margin-top: 100px;">
            <div class="row d-flex justify-content-center">
                <!-- Banner inicial -->
                <div class="col-12" style="height: auto;">
                    <a href="info_produto.php?id=4">
                        <img class="img-fluid" src="img/banner-oferta.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Ofertas de produtos -->
    <div class="container-fluid" style="background-color: #4c4c4c; height: auto; min-height: 50vh;">
        <div class="container">
            <div class="row">
                <div class="col-12 py-3 py-md-5">
                    <h2 class="titulo-ofertas">ALGUMAS DE NOSSAS OFERTAS!</h2>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
            <?php 
            // Contador para manter 3 cards por linha
            $cont = 0;
            while ($row = mysqli_fetch_array($result, MYSQLI_NUM)){
            ?>  
            <!-- Card de produto -->
            <div class="col-12 col-md-4 pb-0 pb-md-3 d-flex justify-content-center">
                <div class="product-card" style="max-width: 80%;">
                    <div class="product-image" style="text-align: center;">
                        <!-- Imagem do produto -->
                        <img class="img-fluid" src="<?php echo $row[5]; ?>">
                    </div>
                    <!-- Informações do produto -->
                    <div class="product-info" style="text-align: left;">
                        <h3 class="product-title"><?php echo $row[2]; ?></h3>
                        <p class="product-price" style="text-decoration: line-through; color: red;">R$ <?php echo number_format($row[3], 2, ',', '.'); ?></p>
                        <p class="product-price">R$ <?php echo number_format($row[7], 2, ',', '.'); ?></p>
                        <?php $porcentagem = (($row[3] - $row[7]) / $row[3]) * 100?>
                        <div class="fixed-tag"><span><?php echo round($porcentagem, 0); ?>% OFF</span></div>
                        <!-- Formulário para adicionar ao carrinho -->
                        <form action="info_produto.php" method="GET" style="text-align: center;">
                            <input type="hidden" name="id" value="<?php echo $row[0]; ?>">
                            <button type="submit" class="btn-buy">Visualizar Oferta</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php 
                // Lógica para fechar linha e manter integridade de 3 cards por linha
                $cont++;
                if ($cont % 3 == 0) {
                    echo '</div><div class="row">';
                }
            ?>
            <?php } ?>
        </div>
        </div>
    </div>

    <?php
    mysqli_close($conn);
    ?>

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
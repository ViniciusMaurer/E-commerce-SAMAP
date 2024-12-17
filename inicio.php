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
    <title>Samap - Produtos</title>
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
                                <a class="nav-link" id="navcolor" href="inicio.php"><text class="selected">INÍCIO</text></a>
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

    // Seleciona as melhores ofertas
    $sql = "SELECT ID, CODIGO, NOME, PRECO, IMG, IMG_CAMINHO, VERIFICA_OFERTA, PRECO_OFERTA, ID_LOJA, 
            (100 - ((PRECO_OFERTA / PRECO) * 100)) PORCENTAGEM 
            FROM PRODUTO 
            WHERE VERIFICA_OFERTA IS NOT NULL 
            ORDER BY PORCENTAGEM DESC 
            LIMIT 4";
    $result = mysqli_query($conn, $sql);

    // Seleciona todos os produtos
    $sql_prod = "SELECT * FROM PRODUTO";
    $result_prod = mysqli_query($conn, $sql_prod);
    ?>

    <div class="container-fluid pb-5" style="background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container" style="margin-top: 100px;">
            <div class="row bg-light d-flex justify-content-center" style="border-radius: 20px; min-height: 30vh;">
                <div class="col-12" style="height: auto;">
                    <h1 class="titulo-samap p-3">MAIORES OFERTAS!</h1>
                </div>
                <div class="row d-flex justify-content-center" style="margin: 0 auto!important;">
                    <?php 
                    $cont = 0;
                    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                    ?>
                    <div class="col-6 col-md-3 d-flex justify-content-center">
                        <div class="product-card" style="max-width: 100%; height: auto;" onclick="document.location = 'info_produto.php?id=<?php echo $row[0]; ?>'">
                            <div class="product-image" style="text-align: center;">
                                <img class="img-fluid" src="<?php echo $row[5]; ?>">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title" title="<?php echo htmlspecialchars($row[2]); ?>">
                                    <?php
                                    // Trunca o nome do produto se exceder o limite de caracteres
                                    $nome_produto = $row[2]; 
                                    $limite_caracteres = 50;
                                    if (strlen($nome_produto) > $limite_caracteres) {
                                        $nome_produto = substr($nome_produto, 0, $limite_caracteres) . '...';
                                    }
                                    echo $nome_produto;
                                    ?>
                                </h3>
                                <?php if ($row[7] != null) { ?>
                                    <p class="product-price">R$ <?php echo number_format($row[7], 2, ',', '.'); ?></p>
                                    <?php $porcentagem = (($row[3] - $row[7]) / $row[3]) * 100; ?>
                                    <div class="fixed-tag"><span><?php echo round($porcentagem, 0); ?>% OFF</span></div>
                                <?php } else { ?>
                                    <p class="product-price">R$ <?php echo number_format($row[3], 2, ',', '.'); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $cont++;
                    if ($cont % 4 == 0) {
                        echo '</div><div class="row" style="margin: 0 auto!important;">';
                    }
                    ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid" style="background-color: #4c4c4c; height: auto; min-height: 50vh;">
        <div class="container">
            <div class="row">
                <div class="col-12 pt-3">
                    <h2 class="titulo-categorias">PRODUTOS</h2>
                </div>
                <div class="row d-flex justify-content-center" style="margin: 0 auto!important;">
                    <?php 
                    $cont = 0;
                    while ($row = mysqli_fetch_array($result_prod, MYSQLI_NUM)) {
                    ?>
                    <div class="col-6 col-md-3 d-flex justify-content-center">
                        <div class="product-card" style="max-width: 100%; height: auto;" onclick="document.location = 'info_produto.php?id=<?php echo $row[0]; ?>'">
                            <div class="product-image" style="text-align: center;">
                                <img class="img-fluid" src="<?php echo $row[5]; ?>">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title" title="<?php echo htmlspecialchars($row[2]); ?>">
                                    <?php
                                    $nome_produto = $row[2]; 
                                    $limite_caracteres = 50;
                                    if (strlen($nome_produto) > $limite_caracteres) {
                                        $nome_produto = substr($nome_produto, 0, $limite_caracteres) . '...';
                                    }
                                    echo $nome_produto;
                                    ?>
                                </h3>
                                <?php if ($row[7] != null) { ?>
                                    <p class="product-price">R$ <?php echo number_format($row[7], 2, ',', '.'); ?></p>
                                    <?php $porcentagem = (($row[3] - $row[7]) / $row[3]) * 100; ?>
                                    <div class="fixed-tag"><span><?php echo round($porcentagem, 0); ?>% OFF</span></div>
                                <?php } else { ?>
                                    <p class="product-price">R$ <?php echo number_format($row[3], 2, ',', '.'); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $cont++;
                    if ($cont % 4 == 0) {
                        echo '</div><div class="row" style="margin: 0 auto!important;">';
                    }
                    ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

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
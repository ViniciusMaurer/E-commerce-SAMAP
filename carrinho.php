<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/icone-samap.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/card.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
                                    <img class="img-fluid" src="img/icone-carrinho-selected.png" alt="Carrinho">
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
		header("Location: index.php");
	}

    if (isset($_POST['remove_product_id'])) {
        $productId = $_POST['remove_product_id'];
        $sqlDelete = "DELETE FROM GRUPO_CARRINHO_PRODUTOS WHERE ID_PRODUTO = $productId";
        mysqli_query($conn, $sqlDelete);
    }

    if (isset($_POST['quantidade']) && isset($_POST['idProdutoUpdate']) && isset($_POST['idCarrinhoUpdate'])) {
        $quantidade = $_POST['quantidade'];
        $produtoIdUpdate = $_POST['idProdutoUpdate'];
        $carrinhoIdUpdate = $_POST['idCarrinhoUpdate'];
        if ($quantidade > 0) {
            $sqlUpdate = "UPDATE GRUPO_CARRINHO_PRODUTOS SET QTD = $quantidade WHERE ID_PRODUTO = $produtoIdUpdate AND ID_CARRINHO = $carrinhoIdUpdate";
            mysqli_query($conn, $sqlUpdate);
        }
    }

    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

    $sql = "SELECT P.ID, P.NOME, P.PRECO, P.PRECO_OFERTA, P.IMG_CAMINHO, P.ID_LOJA, U.ID, C.ID, G.QTD
              FROM GRUPO_CARRINHO_PRODUTOS G,
                   CARRINHO C,
                   PRODUTO P, 
                   USUARIO U
             WHERE G.ID_CARRINHO = C.ID
               AND G.ID_PRODUTO = P.ID
               AND C.ID_USUARIO = U.ID
               AND U.EMAIL = '$usuario'
               AND U.SENHA = '$senha'";
    $result = mysqli_query($conn, $sql);
    $somaTotal = 0;
    ?>

    <div class="container-fluid" style="margin-top: 6.25rem; background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: repeat; height: auto; background-attachment: fixed; min-height: 100vh;">
        <div class="container">
            <div class="row p-2 p-md-5">
                <div class="col-12 text-center">
                    <img class="img-fluid" src="img/carrinho-etapa-1.png" alt="Etapas do Carrinho">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row produto-carrinho">
                <div class="row pb-3 pt-3 pt-md-0">
                    <div class="col-12">
                        <h2 class="titulo-carrinho">SEU CARRINHO DE COMPRAS:</h2>
                    </div>
                </div>
                <?php 
                if(mysqli_num_rows($result) > 0){

                    while($row = mysqli_fetch_array($result, MYSQLI_NUM)) { 
                        
                        $sqlFantasia = "SELECT U.SOBRENOME FROM USUARIO U, PRODUTO P WHERE P.ID_LOJA = U.ID AND P.ID = '$row[0]'";
                        $resultFantasia = mysqli_query($conn, $sqlFantasia);
                        $rowFantasia = mysqli_fetch_assoc($resultFantasia);
                        $nomeFantasia = $rowFantasia['SOBRENOME'];
                        ?>                             
                        
                        <div class="row produto-carrinho mb-2 p-2 mt-2 bg-white" style="border-radius: 2rem;">
                            <div class="col-12 col-md-4 img-produto-carrinho" style="position: relative;">
                                <img class="img-fluid" src="<?php echo $row[4]; ?>" style="max-width: 10rem; height: auto;">
                                <?php if($row[3] != null) { 
                                    $porcentagem = (($row[2] - $row[3]) / $row[2]) * 100; ?>
                                    <div class="fixed-tag"><span><?php echo round($porcentagem, 0); ?>% OFF</span></div>
                                <?php } ?>
                            </div>
                            <div class="col-12 col-md-8 p-3">
                                <div class="py-1 d-flex justify-content-between align-items-center">
                                    <span class="titulo-produto-carrinho"><?php echo $row[1]; ?></span>
                                    <form method="post" style="display:inline;" onsubmit="return confirmRemoval();">
                                        <input type="hidden" name="remove_product_id" value="<?php echo $row[0]; ?>">
                                        <button type="button" style="border: none; background: none;" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row[0]; ?>"><i class="fa-solid fa-trash" style="color: #ff8c00;"></i></button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="modal<?php echo $row[0]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Tem certeza que deseja excluir esse produto?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger" onclick="document.location = 'excluir_produto_carrinho.php?id_excluir=<?php echo $row[0]; ?>'">Confirmar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php if($row[3] != '') { ?>
                                    <h3 class="py-0">
                                        <span class="text-danger" style="text-decoration: line-through; margin-right: 10px;">
                                            R$ <?php echo number_format($row[2], 2, ',', '.'); ?>
                                        </span>
                                        <span class="text-success">
                                            R$ <?php echo number_format($row[3], 2, ',', '.'); 
                                                $somaTotal += $row[3]; ?>
                                        </span>
                                    </h3>
                                <?php } else { ?>
                                    <h3 class="py-1 text-success">
                                        R$ <?php echo number_format($row[2], 2, ',', '.'); 
                                            $somaTotal += $row[2]; ?>
                                    </h3>
                                <?php } ?>
                                <p class="py-0 text-warning">
                                    <b>Vendedor:</b> <?php echo $nomeFantasia; ?>
                                </p>
                                <form action="carrinho.php" method="POST">
                                    <div class="d-flex align-items-center py-1">
                                        <input type="hidden" value="<?php echo $row[0]; ?>" name="idProdutoUpdate">
                                        <input type="hidden" value="<?php echo $row[7]; ?>" name="idCarrinhoUpdate">
                                        <button type="submit" class="btn btn-success btn-sm decrement-btn" name="acao" value="decremento" data-preco="<?php echo ($row[3] != '') ? number_format($row[3], 2, ',', '.') : number_format($row[2], 2, ',', '.'); ?>">-</button>
                                        <input type="text" class="form-control mx-2 quantity-input" style="width: 3.2rem; text-align: center;" value="<?php echo $row[8]; ?>" name="quantidade" readonly>
                                        <button type="submit" class="btn btn-success btn-sm increment-btn" name="acao" value="incremento" data-preco="<?php echo ($row[3] != '') ? number_format($row[3], 2, ',', '.') : number_format($row[2], 2, ',', '.'); ?>">+</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php 
                        $idCarrinho = $row[7];
                    } 
                }else{ ?>
                    <div class="row produto-carrinho mb-2 p-5 mt-2 bg-white" style="border-radius: 2rem;">
                        <div class="col-12 text-center">
                            <h3>Nenhum produto no carrinho :(</h3>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="container pb-2">
            <div class="row p-3">
                <div class="col-12" id="info_carrinho">
                    <div class="row p-3">
                        <div class="col-6">
                            <h5>Valor Total:</h5>
                            <h5 class="text-success" id="total-value">R$ <?php echo number_format($somaTotal, 2, ',', '.'); ?></h5>
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end">
                            <?php
                            if($somaTotal > 0){ ?>
                                <a href="carrinho_entrega.php"><button type="submit" class="btn btn-success">Prosseguir</button></a>
                            <?php } ?>
                        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateTotal() {
                let total = 0;
                document.querySelectorAll('.quantity-input').forEach(input => {
                    const quantity = parseInt(input.value, 10);
                    const price = parseFloat(input.closest('.produto-carrinho').querySelector('.increment-btn').dataset.preco.replace('.', '').replace(',', '.'));
                    total += quantity * price;
                });
                document.getElementById('total-value').innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
            }

            document.querySelectorAll('.decrement-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const quantityInput = this.nextElementSibling;
                    let currentQuantity = parseInt(quantityInput.value, 10);
                    if (currentQuantity > 1) {
                        quantityInput.value = currentQuantity - 1;
                        updateTotal();
                    }
                });
            });

            document.querySelectorAll('.increment-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const quantityInput = this.previousElementSibling;
                    let currentQuantity = parseInt(quantityInput.value, 10);
                    quantityInput.value = currentQuantity + 1;
                    updateTotal();
                });
            });

            updateTotal();
        });
    </script>
    
</body>
</html>
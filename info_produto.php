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
    <title>Samap - Produto</title>
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

    <div class="container-fluid pb-5" style="margin-top: 100px;">
        <?php
        include_once "conexao.php";

        $idProduto = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $sql = "SELECT P.NOME, P.PRECO, P.PRECO_OFERTA, P.IMG_CAMINHO, P.DESCRICAO, U.SOBRENOME AS FANTASIA FROM PRODUTO P, USUARIO U WHERE U.ID = P.ID_LOJA AND P.ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idProduto);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($produto) {
                $nome = $produto['NOME'];
                $preco = $produto['PRECO'];
                $preco_oferta = $produto['PRECO_OFERTA'];
                $caminho_img = $produto['IMG_CAMINHO'];
                $descricao = $produto['DESCRICAO'];
                $fantasia = $produto['FANTASIA'];
            } else {
                echo "<p>Produto não encontrado.</p>";
                exit();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            if (!$conn) {
                echo  "<script>alert('Nao foi possivel conectar ao banco de dados!');</script>";
                header('Location: logout.php');
            }			
        
            if ((!isset($_SESSION["usuario"])) || (!isset($_SESSION["senha"]))) {
                header("Location: index.php");
            }
            
            $idProduto = intval($_POST['id']);
            $nome = $_POST['nome'];
            $preco = floatval($_POST['preco']);
            $preco_oferta = floatval($_POST['preco_oferta']);
            $caminho_img = $_POST['caminho_img'];
            $descricao = $_POST['descricao'];
            $fantasia = $_POST['fantasia'];

            $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
            $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);
            $sql = "SELECT ID FROM USUARIO WHERE EMAIL = '$usuario' AND SENHA = '$senha'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $id_usuario = $row['ID'];

            // Verifica se o usuario tem carrinho 
            $sql = "SELECT GP.ID AS GRUPOID, C.ID AS CARRINHOID 
                    FROM GRUPO_CARRINHO_PRODUTOS GP, CARRINHO C 
                    WHERE C.ID_USUARIO = $id_usuario AND GP.ID_CARRINHO = C.ID ";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $id_grupo_carrinho = $row['GRUPOID'] ?? null;
            $id_carrinho = $row['CARRINHOID'] ?? null;
            // Cria o carrinho se o usuario não tem
            if($id_carrinho == null){
                $sql = "INSERT INTO CARRINHO (ID_USUARIO) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_usuario);
                $stmt->execute();
                $id_carrinho = $conn->insert_id;
            }
            // Cria o grupo_carrinho se o usuario não tem
            if($id_grupo_carrinho == null){
                $sql = "INSERT INTO GRUPO_CARRINHO_PRODUTOS (ID_CARRINHO, ID_PRODUTO) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $id_carrinho, $idProduto);
                $stmt->execute();
                $id_grupo_carrinho = $conn->insert_id;
            }else{
                // Verifica se o usuário está autenticado
                $sql = "SELECT DISTINCT I.ID, GC.ID_CARRINHO
                FROM PRODUTO I,
                    GRUPO_CARRINHO_PRODUTOS GC
                WHERE EXISTS (
                        SELECT 1
                        FROM GRUPO_CARRINHO_PRODUTOS CP,
                            CARRINHO C,
                            USUARIO U
                        WHERE U.EMAIL = ?
                            AND U.SENHA = ?
                            AND C.ID = CP.ID_CARRINHO
                            AND C.ID_USUARIO = U.ID
                            AND CP.ID_PRODUTO = I.ID)
                            AND GC.ID_PRODUTO = I.ID";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $_SESSION["usuario"], $_SESSION["senha"]);
                $stmt->execute();
                $result = $stmt->get_result();

                $validaProdutoUnico = 0;
                $idCarrinho = 0;
                while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                    if($row[0] == $idProduto){
                        $validaProdutoUnico = 1;
                    }
                    if($row[1] != null){
                        $idCarrinho = $row[1];
                    }
                }

                if($validaProdutoUnico == 0)
                {
                    $sqlToCart = "INSERT INTO GRUPO_CARRINHO_PRODUTOS (ID_PRODUTO, ID_CARRINHO) VALUES (?, ?)";
                }
                else
                {
                    $sqlToCart = "UPDATE GRUPO_CARRINHO_PRODUTOS SET QTD = QTD + 1 WHERE ID_PRODUTO = ? AND ID_CARRINHO = ?";
                }

                $stmt = $conn->prepare($sqlToCart);
                $stmt->bind_param("ii", $idProduto, $idCarrinho);
                $stmt->execute();
            }
        }

        $stmt->close();
        mysqli_close($conn);
        ?>

        <div class="row">
            <div class="col-12 col-md-7" style="border-radius: 0px 37px 37px 0px; height: 400px; background: linear-gradient(to left, #167225, #56a162); display: flex; justify-content: center; align-items: center;">
                <img class="img-fluid" src="<?php echo $caminho_img; ?>" style="vertical-align: center; text-align: center;">
            </div>
            <div class="col-12 col-md-5 p-5">
                <form action="info_produto.php" method="POST">
                    <h2 style="margin-bottom: 15px;"><?php echo $nome; ?></h2>                
                    <?php                
                    echo $descricao;
                    ?>
                    <br>
                    <?php if ($preco_oferta > 0): ?>
                        <h2 style="text-decoration: line-through; font-size: 15px; margin-bottom: 0px; margin-top: 15px;">R$ <?php echo number_format($preco, 2, ',', '.'); ?></h2>
                        <h2>R$ <?php echo number_format($preco_oferta, 2, ',', '.'); ?></h2>
                        <?php $porcentagem = (($preco - $preco_oferta) / $preco) * 100; ?>
                        <p class="product-price"><?php echo round($porcentagem, 0); ?>% de Desconto</p>
                    <?php else: ?>
                        <br><br><br>
                        <h2>R$ <?php echo number_format($preco, 2, ',', '.'); ?></h2>
                    <?php endif; ?>
                    <p class="py-0 text-warning">
                        <b>Vendedor:</b> <?php echo $fantasia; ?>
                    </p>
                    <input type="hidden" name="id" value="<?php echo $idProduto; ?>">
                    <input type="hidden" name="nome" value="<?php echo $nome; ?>">
                    <input type="hidden" name="preco" value="<?php echo $preco; ?>">
                    <input type="hidden" name="preco_oferta" value="<?php echo $preco_oferta; ?>">
                    <input type="hidden" name="descricao" value="<?php echo $descricao; ?>">
                    <input type="hidden" name="fantasia" value="<?php echo $fantasia; ?>">
                    <input type="hidden" name="caminho_img" value="<?php echo $caminho_img; ?>">
                    
                    <input type="submit" value="Adicionar ao carrinho">
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 50vh;">
        <div class="row">
            <div class="col-12"></div>
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
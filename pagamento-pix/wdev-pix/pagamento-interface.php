<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../img/icone-samap.png">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../css/card.css">
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
                        <a class="navbar-brand" href="../../inicio.php"><img class="img-fluid" src="../../img/logo-samap.png" alt="Logo Samap"></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav">
                                <a class="nav-link" id="navcolor" href="../../inicio.php">INÍCIO</a>
                                <a class="nav-link" id="navcolor" href="../../ofertas.php">OFERTAS</a>
                                <a class="nav-link" id="navcolor" href="../../contato.php">SUPORTE</a>
                                <a class="nav-link" id="navcolor" href="../../carrinho.php">
                                    <img class="img-fluid" src="../../img/icone-carrinho-selected.png" alt="Carrinho">
                                </a>
                                <?php
                                session_start(); 
                                if(isset($_SESSION['usuario']) && $_SESSION['usuario'] != null) { ?>
                                    <div class="dropdown">
                                        <a class="nav-link" id="navcolor" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img class="img-fluid" src="../../img/icone-conta.png" alt="Conta">
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-perfil" aria-labelledby="dropdownMenuLink">
                                            <li class="dropdown-item-perfil"><a class="dropdown-item dropdown-item-perfil" href="../../perfil.php">Perfil</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li class="dropdown-item-perfil"><a class="dropdown-item dropdown-item-perfil" href="../../logout.php">Sair</a></li>
                                        </ul>
                                    </div>
                                <?php } 
                                else{ ?>
                                    <a class="nav-link" id="navcolor" href="../../perfil.php">
                                        <img class="img-fluid" src="../../img/icone-conta.png" alt="Perfil">
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
    include_once "../../conexao.php";

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
    $id_usuario = $row['ID'];

    require __DIR__.'/vendor/autoload.php';

    use \App\Pix\Payload;

    $valorPedido = $_POST['valor'];
    $enderecoSelecionado = $_POST['endereco'];
    $entregaSelecionada = $_POST['entrega'];

    // SELECT endereco completo
    $sql = "SELECT E.RUA, E.NUMERO, E.BAIRRO, E.COMPLEMENTO, E.CIDADE, E.CEP, ES.UF AS ESTADOUF, P.NOME AS PAIS 
            FROM ENDERECO E, ESTADO ES, PAIS P 
            WHERE E.ID = '$enderecoSelecionado'
              AND ES.ID = E.ID_ESTADO
              AND ES.ID_PAIS = P.ID ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $rua = $row['RUA'];
    $numero = $row['NUMERO'];
    $bairro = $row['BAIRRO'];
    $complemento = $row['COMPLEMENTO'];
    $cidade = $row['CIDADE'];
    $cep = $row['CEP'];
    $estadoUF = $row['ESTADOUF'];
    $pais = $row['PAIS'];

    // SELECT transportadora
    $sql = "SELECT NOME FROM TRANSPORTADORA WHERE ID = '$entregaSelecionada'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $entrega = $row['NOME'];

    // Verifica se o valor é um número e maior que 0
    if (!is_numeric($valorPedido) || $valorPedido <= 0) {
        die("Valor inválido para o pedido.");
    }

    // Formata o valor para 2 casas decimais
    $valorFormatado = number_format((float)$valorPedido, 2, '.', '');

    //INSTANCIA PRINCIPAL DO PAYLOAD PIX
    $obPayload = (new Payload)->setPixKey('35185ee3-1238-4157-a635-8f2973a13a8b')
                            ->setDescription('Pagamento do pedido SAMAP')
                            ->setMerchantName('VINICIUS MAURER')
                            ->setMerchantCity('BRASILIA')
                            ->setAmount($valorFormatado)
                            ->setTxid('SAMAP');

    // CODIGO DE PAGAMENTO PIX
    $payloadQrCode = $obPayload->getPayload();
    
    // INSERE INFORMAÇÕES NO PEDIDO
    $sqlToCart = "INSERT INTO PEDIDO (ID_USUARIO, ID_ENDERECO_ENTREGA, ID_TRANSPORTADORA, ID_STATUS_PEDIDO) VALUES (?, ?, ?, 2)";
    $stmt = $conn->prepare($sqlToCart);
    $stmt->bind_param("iii", $id_usuario, $enderecoSelecionado, $entregaSelecionada);
    $stmt->execute();
    $id_pedido = $conn->insert_id;
    $stmt->close();

    // SELECT PRODUTOS CARRINHO
    $sql = "SELECT G.ID_PRODUTO FROM GRUPO_CARRINHO_PRODUTOS G, CARRINHO C, USUARIO U WHERE U.ID = '$id_usuario' AND G.ID_CARRINHO = C.ID ";
    $result = mysqli_query($conn, $sql);
    // INSERT GRUPO_PEDIDO_PRODUTO
    while($row = mysqli_fetch_array($result, MYSQLI_NUM)) { 
        $sqlToCart = "INSERT INTO GRUPO_PEDIDO_PRODUTO (ID_PEDIDO, ID_PRODUTO) VALUES (?, ?)";
        $stmt = $conn->prepare($sqlToCart);
        $stmt->bind_param("ss", $id_pedido, $row[0]);
        $stmt->execute();
    }

    $stmt->close();
    mysqli_close($conn);

    ?>
    <div class="container-fluid" style="margin-top: 6.25rem; background-image: url('../../img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: repeat; height: auto; background-attachment: fixed; min-height: 100vh;">
        <div class="container">
            <div class="row p-2 p-md-5">
                <div class="col-12 text-center">
                    <img class="img-fluid" src="../../img/carrinho-etapa-3.png" alt="Etapas do Carrinho">
                </div>
            </div>
        </div>
        <div class="container bg-light" style="padding: 20px; border-radius: 30px;">
            <div class="row pt-3 pt-md-0">
                <div class="col-12 col-md-4" style="text-align: center;">
                    <h2 class="titulo-carrinho">REALIZE O PAGAMENTO PIX</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4" style="text-align: center;">
                    <div class="mb-3">
                        <img class="img-fluid" style="border-radius: 20px;" src="pagamento.php?valor=<?php echo $valorPedido; ?>&nroPedido=<?php echo $id_pedido; ?>" alt="QR Code de Pagamento" class="qrcode">
                    </div>
                    <div style="text-align: left; padding-left: 20px;" id="mensagemRetorno"></div>
                    <div class="input-group" style="padding: 20px;">
                        <input type="text" class="form-control" id="codigopix" name="codigopix" value="<?php echo $payloadQrCode; ?>" aria-describedby="basic-addon2" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="copiarButton" onclick="copiarTexto()">Copiar</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8" style="padding: 20px; ">
                    <h2>PEDIDO Nº <?php echo $id_pedido; ?></h2>
                    <br>
                    <h3>Valor (R$): <?php echo number_format($valorFormatado, 2, ',', '.'); ?></h3>
                    <br>
                    <h4>Local de entrega: </h4>
                    <h5><?php echo $rua.", ".$numero.", ".$complemento.", ".$bairro.", ".$cidade.", ".$estadoUF.", ".$cep.", ".$pais; ?></h5>
                    <br>
                    <h4>Método de entrega: </h4>
                    <h5><?php echo $entrega; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <script>
        function copiarTexto() {
            var inputTexto = document.getElementById("codigopix");
            var mensagemRetorno = document.getElementById("mensagemRetorno");

            mensagemRetorno.textContent = "";

            inputTexto.select();
            inputTexto.setSelectionRange(0, 99999); 

            try {
                var sucesso = document.execCommand("copy");
                if (sucesso) {
                    mensagemRetorno.textContent = "Copiado!";
                    mensagemRetorno.style.color = "green";

                    setTimeout(() => {
                        mensagemRetorno.textContent = "";
                    }, 3000);
                } else {
                    mensagemRetorno.textContent = "Falha ao copiar o texto.";
                    mensagemRetorno.style.color = "red";
                }
            } catch (err) {
                console.error("Erro ao copiar o texto: ", err);
                alert("Erro ao copiar o texto.");
            }
        }
    </script>
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
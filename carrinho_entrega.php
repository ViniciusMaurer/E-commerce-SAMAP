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
		header("Location: index.html");
	}

    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

    $sql = "SELECT ID FROM USUARIO WHERE EMAIL = '$usuario' AND SENHA = '$senha'";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $usuario_id = $row['ID'];

    $sql = "SELECT G.ID_PRODUTO, G.QTD, P.PRECO, P.PRECO_OFERTA 
              FROM GRUPO_CARRINHO_PRODUTOS G, PRODUTO P, CARRINHO C
             WHERE G.ID_CARRINHO = C.ID
               AND G.ID_PRODUTO = P.ID
               AND C.ID_USUARIO = $usuario_id";
    $result = mysqli_query($conn, $sql);

    $somaProdutos = 0;
    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
        $qtdProduto = $row[1];
        $preco = $row[2];
        $preco_oferta = $row[3];

        if($preco_oferta != null){
            $somaProdutos += $preco_oferta * $qtdProduto;
        }
        else{
            $somaProdutos += $preco * $qtdProduto;
        }
    }

    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

    $sql = "SELECT ID FROM USUARIO WHERE EMAIL = ? AND SENHA = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ss', $usuario, $senha);
        
        mysqli_stmt_execute($stmt);
        
        mysqli_stmt_bind_result($stmt, $idUsuario);
        mysqli_stmt_fetch($stmt);

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Erro na preparação da consulta!');</script>";
    }

    if (isset($idUsuario)) {
        // Array para armazenar as informações de todos os endereços
        $enderecosInfo = [];
        
        // Consulta para obter todos os endereços associados ao usuário
        $sql = "SELECT ID_ENDERECO FROM GRUPO_ENDERECO_USUARIO WHERE ID_USUARIO = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, 'i', $idUsuario);
            mysqli_stmt_execute($stmt);
            
            // Obter todos os IDs de endereços
            mysqli_stmt_bind_result($stmt, $idEndereco);
            
            // Array para armazenar IDs de endereços
            $enderecos = [];
            while (mysqli_stmt_fetch($stmt)) {
                $enderecos[] = $idEndereco;
            }
    
            mysqli_stmt_close($stmt);
    
            if (!empty($enderecos)) {
                $sql = "SELECT E.ID ID_ENDERECO,
                               E.RUA,
                               E.NUMERO,
                               IFNULL(E.COMPLEMENTO, 'Sem complemento') COMPLEMENTO,
                               E.BAIRRO,
                               E.CIDADE,
                               E.CEP,
                               ES.ID ESTADO_ID,
                               ES.NOME ESTADO,
                               ES.UF,
                               P.NOME PAIS
                          FROM ENDERECO E,
                               ESTADO ES,
                               PAIS P
                         WHERE E.ID = ?
                           AND E.ID_ESTADO = ES.ID
                           AND ES.ID_PAIS = P.ID";
                
                foreach ($enderecos as $idEndereco) {
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, 'i', $idEndereco);
                        mysqli_stmt_execute($stmt);
                        
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if ($row = mysqli_fetch_assoc($result)) {
                            $enderecosInfo[] = [
                                'id' => $row['ID_ENDERECO'],
                                'rua' => $row['RUA'],
                                'numero' => $row['NUMERO'],
                                'bairro' => $row['BAIRRO'],
                                'complemento' => $row['COMPLEMENTO'],
                                'cidade' => $row['CIDADE'],
                                'cep' => $row['CEP'],
                                'estado_id' => $row['ESTADO_ID'],
                                'estado' => $row['ESTADO'],
                                'pais' => $row['PAIS']
                            ];
                        }
    
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<script>alert('Erro na preparação da consulta!');</script>";
                    }
                }
            } 
        } else {
            echo "<script>alert('Erro na preparação da consulta!');</script>";
        }
    }
    ?>

    <div class="container-fluid" style="margin-top: 6.25rem; background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: repeat; height: auto; background-attachment: fixed; min-height: 100vh;">
        <div class="container">
            <div class="row p-2 p-md-5">
                <div class="col-12 text-center">
                    <img class="img-fluid" src="img/carrinho-etapa-2.png" alt="Etapas do Carrinho">
                </div>
            </div>
        </div>
        <div class="container bg-light" style="padding: 10px; border-radius: 30px;">
            <div class="row pt-3 pt-md-0">
                <div class="col-12">
                    <h2 class="titulo-carrinho">ENDEREÇO DE ENTREGA:</h2>
                </div>
            </div>
            <div class="row" style="padding: 20px;">
                <?php
                    if($enderecosInfo != null){
                        $opcao = 0;?>
                        <?php foreach ($enderecosInfo as $info) { ?>
                            <form action="editar_endereco.php" method="POST">
                                <?php 
                                $opcao++; 
                                if($opcao == 1){ ?>
                                    <input type="radio" id="radioEndereco<?php echo $opcao; ?>" name="opcaoEndereco" value="<?php echo $opcao; ?>"  onclick="syncRadios(this)" checked>
                                <?php }
                                else{ ?>
                                    <input type="radio" id="radioEndereco<?php echo $opcao; ?>" name="opcaoEndereco" value="<?php echo $opcao; ?>"  onclick="syncRadios(this)">
                                <?php }
                                ?>
                                <span><?php echo $info['rua'] . ", " ?></span>
                                <span><?php echo $info['numero'] . ", " ?></span>
                                <span><?php echo $info['bairro'] . ", " ?></span>
                                <span><?php echo $info['complemento'] . ", " ?></span>
                                <span><?php echo $info['cidade'] . ", " ?></span>
                                <span><?php echo $info['cep'] . ", " ?></span>
                                <span><?php echo $info['estado'] . ", " ?></span>
                                <span style="margin-right: 10px;"><?php echo $info['pais']; ?></span>
                                <span>
                                    <button type="button" style="border: none; background: none;" data-bs-toggle="modal" data-bs-target="#modal<?php echo $info['id']; ?>"><i class="fa-solid fa-trash" style="color: #ff8c00;"></i></button>
                                </span>
                                <!-- Modal -->
                                <div class="modal fade" id="modal<?php echo $info['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Tem certeza que deseja excluir esse registro?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-danger" onclick="document.location = 'excluir_endereco.php?id_excluir=<?php echo $info['id']; ?>&pagina=carrinho_entrega'">Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <input type="hidden" value="carrinho_entrega" name="paginaSelecionada">
                                <input type="hidden" value="<?php echo htmlspecialchars($info['id']); ?>" name="idEndereco">
                                <button class="btn" style="color: #ff8c00" type="submit">Editar Endereço</button>
                                <br>
                                <br>
                            </form>
                        <?php } ?>
                    <?php } ?>
                <div style="float: left;">
                    <a href="adicionar_novo_endereco.php?pagina=carrinho"><button class="btn text-primary" >+ Adicionar novo endereço</button></a>
                </div>
            </div>
            <div class="row pt-3 pt-md-0">
                <div class="col-12">
                    <h2 class="titulo-carrinho">MÉTODO DE ENTREGA:</h2>
                </div>
            </div>
            <?php
            $sql = "SELECT ID, NOME FROM TRANSPORTADORA";
            $result = mysqli_query($conn, $sql);
            $count = 0;
            ?>
            <div class="row" style="padding: 20px;">
                <form action="..." method="POST">
                <?php while($row = mysqli_fetch_array($result, MYSQLI_NUM)) { 
                    $count++;
                    if($count == 1){
                    ?>    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="opcaoEntrega" id="flexRadioDefault1" value="<?php echo $row[0]; ?>" checked>
                        <label class="form-check-label" for="flexRadioDefault1">
                            <?php echo strtoupper($row[1]); ?>
                        </label>
                    </div>
                    <?php } 
                    else { ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="opcaoEntrega" id="flexRadioDefault1" value="<?php echo $row[0]; ?>">
                        <label class="form-check-label" for="flexRadioDefault1">
                            <?php echo strtoupper($row[1]); ?>
                        </label>
                    </div>
                    <?php } ?>
                    
                <?php } ?>
                </form>
            </div>
        </div>
        <div class="container pb-2">
            <div class="row p-3">
                <div class="col-12" id="info_carrinho">
                    <div class="row p-3">
                        <div class="col-6">
                            <h5>Valor Total:</h5>
                            <h5 class="text-success" id="total-value">R$ <?php echo number_format($somaProdutos, 2, ',', '.'); ?></h5>
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end">
                            <?php 
                                if($enderecosInfo != null){ ?>
                                    <form action="pagamento-pix/wdev-pix/pagamento-interface.php" method="POST" onsubmit="return mostrarOpcaoSelecionada()">
                                        <input type="hidden" name="valor" value="<?php echo $somaProdutos; ?>">
                                        <input type="hidden" id="endereco" name="endereco" value="">
                                        <input type="hidden" id="entrega" name="entrega" value="">
                                        <button class="btn btn-success" type="submit">Prosseguir</button>
                                    </form>
                            <?php 
                            } 
                            else { ?>
                                <form action="adicionar_novo_endereco.php?pagina=carrinho" method="POST">
                                    <button class="btn btn-success" type="submit">Prosseguir</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const enderecosInfo = <?php echo json_encode($enderecosInfo); ?>;

        function getSelectedRadioValue(name) {
            const radios = document.getElementsByName(name);
            
            for (let radio of radios) {
                if (radio.checked) {
                    return radio.value;
                }
            }

            return null;
        }

        function mostrarOpcaoSelecionada() {
            var opcaoEndereco = getSelectedRadioValue("opcaoEndereco");

            var opcaoEntrega = getSelectedRadioValue("opcaoEntrega");

            const selectedAddress = enderecosInfo[opcaoEndereco - 1];

            if (selectedAddress) {
                const enderecoString = `${selectedAddress.id}`;
                
                document.getElementById("endereco").value = enderecoString;
                document.getElementById("entrega").value = opcaoEntrega;
                return true;  
            } else {
                alert("Por favor, selecione um endereço.");
                return false; 
            }
        }

        function syncRadios(selectedRadio) {
            const radios = document.querySelectorAll('input[name="opcaoEndereco"]');
            radios.forEach(radio => {
                radio.checked = false;
            });
            selectedRadio.checked = true; 
        }
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
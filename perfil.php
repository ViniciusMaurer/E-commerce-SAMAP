<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/icone-samap.png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <title>Perfil</title>
</head>

<?php
    include_once "sessao.php";
                        
    $usuario = mysqli_real_escape_string($conn, $_SESSION["usuario"]);
    $senha = mysqli_real_escape_string($conn, $_SESSION["senha"]);

    $sql = "SELECT U.ID, 
                   U.NOME, 
                   U.SOBRENOME, 
                   U.DATA_INICIO, 
                   U.EMAIL, 
                   U.TELEFONE, 
                   DATE_FORMAT(U.DATA_NASC, '%d/%m/%Y') DATA_NASC, 
                   U.CPF,  
                   U.VERIFICA_VENDEDOR, 
                   IFNULL(U.CAMINHO_IMAGEM, 'imagens/usuarios/unknow.png') CAMINHO_IMAGEM
              FROM USUARIO U
             WHERE U.EMAIL = '$usuario' 
               AND U.SENHA = '$senha' ";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    $id = $row['ID'];
    $nome = $row['NOME'];
    $sobrenome = $row['SOBRENOME'];
    $dh = $row['DATA_INICIO'];
    $data = date_create($dh);
    $email = $row['EMAIL'];
    $telefone = $row['TELEFONE'];
    $data_nasc = $row['DATA_NASC'];
    $cpf = $row['CPF'];
    $verifica_vendedor = $row['VERIFICA_VENDEDOR'];
    $caminho_imagem = $row['CAMINHO_IMAGEM'];
?>

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
                                if(isset($_SESSION['usuario']) && $_SESSION['usuario'] != null) { ?>
                                    <div class="dropdown">
                                        <a class="nav-link" id="navcolor" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img class="img-fluid" src="img/icone-conta-selec.png" alt="Conta">
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
                                        <img class="img-fluid" src="img/icone-conta-selec.png" alt="Perfil">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Linha com imagem de ondas no fundo -->
    <div class="container-fluid" style="background-image: url('img/fundo-samap.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container" style="height: 300px;">
            <div class="row">
                <div class="col-12"></div>
            </div>
        </div>
    </div>

    <!-- Card do usuário e linha com tipo de vendedor -->
    <div class="container-fluid" style="background-color: #4c4c4c;">
        <div class="container" style="max-height: 70px;">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="bg-white pb-3 imagem-perfil">
                        <div class="d-flex justify-content-center p-2">
                            <div id="imageContainer">
                                <img class="img-fluid" id="foto-perfil" style="height: 200px; width: 200px; object-fit: cover; border-radius: 50%; overflow: hidden;" src="<?php echo $caminho_imagem; ?>" alt="">
                            
                                <div id="uploadButton" style="display:none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                    <button onclick="document.getElementById('imageUpload').click();" style="border: none; border-radius: 4px; background-color: #DD1C1A; color: white">Editar imagem</button>
                                </div>
                            </div>

                            <input type="file" id="imageUpload" accept="image/*" style="display:none;" onchange="loadFile(event)">

                            <script>
                                // Função para mostrar o botão de upload ao passar o mouse sobre a imagem
                                document.getElementById('imageContainer').onmouseover = function() {
                                    document.getElementById('uploadButton').style.display = 'block';
                                    document.getElementById('foto-perfil').style.filter = 'brightness(50%)';
                                };

                                // Função para esconder o botão de upload ao tirar o mouse da imagem
                                document.getElementById('imageContainer').onmouseout = function() {
                                    document.getElementById('uploadButton').style.display = 'none';
                                    document.getElementById('foto-perfil').style.filter = 'none';
                                };

                                // Função para carregar a nova imagem selecionada
                                function loadFile(event) {
                                    var output = document.getElementById('foto-perfil');
                                    output.src = URL.createObjectURL(event.target.files[0]);
                                    output.onload = function() {
                                        URL.revokeObjectURL(output.src); // Libera a memória
                                    };
                                }
                            </script>
                        </div>
                        <div style="text-align: center;">
                            <h5 class="pt-1">
                                <span> <?php echo $verifica_vendedor != 1 ? $nome . " " : ""; echo $sobrenome; ?> </span>
                                <span class="pb-1">
                                    <img src="img/verificado.png" alt="">
                                </span>
                            </h5>
                            <p style="color: #ccc;">Entrou dia <?php echo date_format($data, "d/m/Y");?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9 ">
                    <div class="p-2 funcao-perfil">
                        <h2 class="titulo-categorias"><?php echo $verifica_vendedor != "1" ? "CLIENTE" : "VENDEDOR"; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo com informações sobre o usuário -->
    <div class="container-fluid" style="background-color: #FFFCAC; height: auto;">
        <div class="container pb-4">
            <div class="row">
                <div class="col-12 col-md-3">
                    <!-- Espaço do card de usuário -->
                </div>
                <div class="col-12 col-md-9 margin300">
                    <form action="editar_perfil.php" method="POST">
                        <br>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn me-md-2" style="background-color: #FF8C00; color: #ffffff" type="submit">Editar Perfil</button>
                            <?php
                            if($verifica_vendedor == "1"){ ?>
                                <a href="adicionar_produto.php"><button class="btn me-md-2" style="background-color: #FF8C00; color: #ffffff" type="button">Adicionar Produto</button></a>
                            <?php } ?>
                        </div>
    
                        <h5 class="pt-3">
                        <?php
                        if($verifica_vendedor == 1){
                            echo "Razão Social";
                        }else{
                            echo "Nome";
                        }
                        ?>
                        </h5>
                        <input type="text" name="nome" readonly class="input-perfil col-12 col-md-10" autocomplete="off" value="<?php echo $nome;?>">
                        
                        <h5 class="pt-3">
                        <?php
                        if($verifica_vendedor == 1){
                            echo "Nome Fantasia";
                        }else{
                            echo "Sobrenome";
                        }
                        ?>
                        </h5>
                        <input type="text" name="sobrenome" readonly class="input-perfil col-12 col-md-10" autocomplete="off" value="<?php echo $sobrenome;?>">

                        <h5 class="pt-3">Email</h5>
                        <input type="text" name="email" readonly class="input-perfil col-12 col-md-10" autocomplete="off" value="<?php echo $email;?>">
                        
                        <h5 class="pt-3">Telefone</h5>
                        <input type="text" name="telefone" readonly class="input-perfil col-12 col-md-10" autocomplete="off" value="<?php echo $telefone;?>">
    
                        <h5 class="pt-3">
                        <?php
                        if($verifica_vendedor == 1){
                            echo "Data de Fundação";
                        }else{
                            echo "Data de Nascimento";
                        }
                        ?>
                        </h5>
                        <input type="text" name="nascimento" readonly class="input-perfil col-12 col-md-10" autocomplete="off" value="<?php echo $data_nasc;?>">
    
                        <h5 class="pt-3">
                        <?php
                        if($verifica_vendedor == 1){
                            echo "CNPJ";
                        }else{
                            echo "CPF";
                        }
                        ?>
                        </h5>
                        <input type="text" name="cpf" readonly class="input-perfil col-12 col-md-10" autocomplete="off" value="<?php echo $cpf;?>">
                    
                        <input type="hidden" name="verifica_vendedor" value="<?php echo $verifica_vendedor; ?>">
                    </form>

                    <?php
                    $sql = "SELECT E.RUA, E.NUMERO, E.BAIRRO, E.COMPLEMENTO, E.CIDADE, E.CEP, ES.UF, P.NOME, E.ID, ES.ID, ES.NOME
                              FROM ENDERECO E, GRUPO_ENDERECO_USUARIO G, ESTADO ES, PAIS P, USUARIO U
                             WHERE E.ID = G.ID_ENDERECO
                               AND ES.ID = E.ID_ESTADO
                               AND ES.ID_PAIS = P.ID
                               AND G.ID_USUARIO = U.ID
                               AND U.EMAIL = '$usuario' 
                               AND U.SENHA = '$senha' ";
                    $result = mysqli_query($conn, $sql); 

                    if ($result && mysqli_num_rows($result) > 0) {
                    ?>
                    <h5 class="pt-3">Endereço</h5>
                    <?php while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) { ?>
                        <form action="editar_endereco.php" method="POST">
                            <span><?php echo $row[0] . ", " ?></span>
                            <span><?php echo $row[1] . ", " ?></span>
                            <span><?php echo $row[2] . ", " ?></span>
                            <span><?php echo $row[3] . ", " ?></span>
                            <span><?php echo $row[4] . ", " ?></span>
                            <span><?php echo $row[5] . ", " ?></span>
                            <span><?php echo $row[6] . ", " ?></span>
                            <span><?php echo $row[7]; ?></span>
                            <span>
                                <button type="button" style="border: none; background: none;" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row[8]; ?>"><i class="fa-solid fa-trash" style="color: #ff8c00;"></i></button>
                            </span>
                            <!-- Modal -->
                            <div class="modal fade" id="modal<?php echo $row[8]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <button type="button" class="btn btn-danger" onclick="document.location = 'excluir_endereco.php?id_excluir=<?php echo $row[8]; ?>&pagina=perfil'">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <input type="hidden" value="<?php echo $row[8]; ?>" name="idEndereco">
                            <input type="hidden" value="perfil" name="paginaSelecionada">
                            <button class="btn" style="color: #ff8c00" type="submit">Editar Endereço</button>
                            <br>
                        </form>
                    <?php
                        }
                    }
                    ?>
                    <br>
                    <div style="float: left;">
                        <a href="adicionar_novo_endereco.php?pagina=perfil"><button class="btn text-primary" >+ Adicionar novo endereço</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("imageUpload").addEventListener("change", function() {
            if (this.files.length > 0) { 
                var fileName = this.files[0].name;
                <?php 
                $sql = "UPDATE";
                ?>
            }
        });
    </script>

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
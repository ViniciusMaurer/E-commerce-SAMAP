<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/icone-samap.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <title>Criar Conta Samap</title>
</head>
<body>
    <?php 
    include_once "conexao.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $senha = $_POST['senha'];
        $senha1 = $_POST['senha1'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $data = $_POST['data'];
        $cpf = $_POST['cpf'];
        $usuario = $_POST['usuario'];

        // Protege contra SQL injection
        $nome = mysqli_real_escape_string($conn, $nome);
        $sobrenome = mysqli_real_escape_string($conn, $sobrenome);
        $senha = mysqli_real_escape_string($conn, $senha);
        $senha1 = mysqli_real_escape_string($conn, $senha1);
        $email = mysqli_real_escape_string($conn, $email);
        $telefone = mysqli_real_escape_string($conn, $telefone);
        $data = mysqli_real_escape_string($conn, $data);
        $cpf = mysqli_real_escape_string($conn, $cpf);
        $usuario = mysqli_real_escape_string($conn, $usuario);

        // Verifica se as senhas coincidem
        if ($senha !== $senha1) {
            $erro = "As senhas não coincidem!";
        } else {
            // Verifica se o e-mail ou usuário já estão cadastrados
            $sql = "SELECT * FROM USUARIO WHERE EMAIL = '$email'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $erro = "E-mail ou nome de usuário já cadastrado!";
            } else {
                if($usuario == 'vendedor'){
                    $sql = "INSERT INTO USUARIO (NOME, SOBRENOME, DATA_NASC, CPF, EMAIL, SENHA, TELEFONE, VERIFICA_VENDEDOR) VALUES ('$nome', '$sobrenome', '$data', '$cpf', '$email', '$senha', '$telefone', '1')";
                }
                else{
                    $sql = "INSERT INTO USUARIO (NOME, SOBRENOME, DATA_NASC, CPF, EMAIL, SENHA, TELEFONE) VALUES ('$nome', '$sobrenome', '$data', '$cpf', '$email', '$senha', '$telefone')";
                }
 
                if (mysqli_query($conn, $sql)) {

                    $id = 0;
                    $sql = "SELECT ID FROM USUARIO WHERE EMAIL = '$email' AND SENHA = '$senha'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    $id = $row['ID'];

                    if ($id==0) {
                        $erro = "Erro ao criar a conta. Tente novamente!";
                    } else {
                        $sql = "INSERT INTO CARRINHO (ID_USUARIO) VALUES ($id)";
                        $result = mysqli_query($conn, $sql);

                        if (!$result) {
                            $erro = "Erro ao criar a conta. Tente novamente!";
                        }
                    }

                } else {

                    $erro = "Erro ao criar a conta. Tente novamente!";

                }
            }
        }
        header("Location: index.php");
    }
    ?>

    <div class="container-fluid" style="height: wrap_content; overflow: hidden;">
        <div class="row">
            <!-- Coluna da esquerda -->
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center" style="height: 100vh; background-color: #FFFCAC;">
                <div class="row pt-5 pb-5">
                    <div class="col-12 text-center">
                        <img src="img/logo-samap.png" alt="Logo Samap">
                    </div>
                </div>
                <div class="row pb-3">
                    <div class="col-12 text-center">
                        <h1 class="titulo-login">NÓS DESEJAMOS BOAS VINDAS</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 pt-5 px-3 text-center">
                        <h2 id="texto-login" style="color: #FF8C00;">
                            Bem vindo à Samap! Por favor, 
                            <a href="index.php#login-conta" style="text-decoration: none; color: green;">faça login</a> ou 
                            <a href="criar_conta.php#criar-conta" style="text-decoration: none; color: green;">crie sua conta</a>.
                        </h2>
                    </div>
                </div>
            </div>
            <!-- Coluna da direita (criar conta) -->
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center" style="height: auto;">
                <section id="criar-conta">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1 class="titulo-login" style="color: #459653!important">Criar Conta</h1>
                        </div>
                    </div>
                    <div class="row pt-3 pb-3">
                        <div class="col-12 text-center">
                            <div class="col-6 mx-auto">
                                <div class="row justify-content-center">
                                    <div class="col-12 text-center">
                                        <div class="toggle-switch" data-active="cliente">
                                            <button id="clienteBtn" class="toggle-button active" onclick="toggleSwitch('cliente')">Cliente</button>
                                            <button id="vendedorBtn" class="toggle-button" onclick="toggleSwitch('vendedor')">Vendedor</button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <form action="criar_conta.php" method="POST">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <input class="input-email" type="text" id="nome" name="nome" placeholder="Nome" autocomplete="off" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input class="input-email" type="text" id="sobrenome" name="sobrenome" placeholder="Sobrenome" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="input-email" type="email" id="email" name="email" placeholder="E-mail" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <input class="input-email" type="text" id="telefone" name="telefone" placeholder="Telefone" autocomplete="off" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input class="input-email" type="date" id="data" name="data"  placeholder="Data fundação dd/mm/yyyy" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="input-email" type="text" id="cpf" name="cpf" placeholder="CPF" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <input class="input-senha" type="password" name="senha" id="senha" placeholder="Senha" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input class="input-senha" type="password" name="senha1" id="senha1" placeholder="Confirme sua senha" required>
                                        </div>
                                    </div>
                                    <br>
                                    <input type="hidden" name="usuario" value="cliente" id="validacao">
                                    <div class="text-start">
                                        <input type="checkbox" id="mostrarSenhaCheckbox">
                                        <label for="mostrarSenhaCheckbox">Mostrar Senha</label>
                                    </div>
                                    <br>

                                    <input type="submit" value="Registrar">
                                    <br><br>
                                    Já é cliente Samap? <a href="index.php#login-conta" style="text-decoration: none;">Faça seu login!</a>
                                </form>
                                <?php 
                                if (isset($erro)) {
                                    echo "<script>alert('" . addslashes($erro) . "');</script>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Mostrar as senhas -->
    <script>
        const checkboxMostrarSenha = document.getElementById('mostrarSenhaCheckbox');
        const campoSenha = document.getElementById('senha');
        const campoSenha1 = document.getElementById('senha1');

        checkboxMostrarSenha.addEventListener('change', function() {
            const tipo = checkboxMostrarSenha.checked ? "text" : "password";
            campoSenha.type = tipo;
            campoSenha1.type = tipo;
        });

        function toggleSwitch(mode) {
            const nomeInput = document.getElementById('nome');
            const sobrenomeInput = document.getElementById('sobrenome');
            const cpfInput = document.getElementById('cpf');
            const validacaoInput = document.getElementById('validacao');

            if(mode == 'cliente'){
                nomeInput.placeholder = "Nome";
                sobrenomeInput.placeholder = "Sobrenome";
                cpfInput.placeholder = "CPF";
                validacaoInput.value = "cliente";
            } 
            if(mode == 'vendedor'){
                nomeInput.placeholder = "Razão Social";
                sobrenomeInput.placeholder = "Nome Fantasia";
                cpfInput.placeholder = "CNPJ";
                validacaoInput.value = "vendedor";
            }
        }

        const clienteBtn = document.getElementById('clienteBtn');
        const vendedorBtn = document.getElementById('vendedorBtn');

        function toggleActive(activeBtn, inactiveBtn) {
            activeBtn.classList.add('active');
            inactiveBtn.classList.remove('active');
        }

        clienteBtn.addEventListener('click', () => toggleActive(clienteBtn, vendedorBtn));
        vendedorBtn.addEventListener('click', () => toggleActive(vendedorBtn, clienteBtn));
    </script>
    
</body>
</html>
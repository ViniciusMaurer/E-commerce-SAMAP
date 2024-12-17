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
    <title>Login Samap</title>
</head>
<body>
    <?php 
    session_start();

    include_once "conexao.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Protege contra SQL injection
        $email = mysqli_real_escape_string($conn, $email);
        $senha = mysqli_real_escape_string($conn, $senha);

        $sql = "SELECT * FROM USUARIO WHERE EMAIL = '$email' AND SENHA = '$senha'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
		    $_SESSION['usuario'] = $email;
		    $_SESSION['senha'] = $senha;

            header("Location: inicio.php");

            exit();
        } else {
            $erro = "Email ou senha incorretos!";
        }
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
            <!-- Coluna da direita (login) -->
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center" style="height: 100vh;">
                <section id="login-conta">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1 class="titulo-login" style="color: #459653!important">Login</h1>
                        </div>
                    </div>
                    <div class="row pt-3 pb-3">
                        <div class="col-12 text-center">
                            <div class="col-6 mx-auto">
                                <form action="index.php" method="POST">
                                    <input class="input-email" id="emailinput" type="text" name="email" placeholder="E-mail" autocomplete="off">
                                    <br><br>
                                    <input class="input-senha" type="password" name="senha" id="senha" placeholder="Senha">
                                    <br><br>
                                    <div class="text-start">
                                        <input type="checkbox" id="mostrarSenhaCheckbox">
                                        <label for="mostrarSenhaCheckbox">Mostrar Senha</label>
                                    </div>
                                    <br>
                                    <input type="submit" value="Entrar">
                                    <br><br>
                                    Não tem uma conta? <a href="criar_conta.php#criar-conta" style="text-decoration: none;">Registre-se agora!</a>
                                </form>
                                <?php
                                if (isset($erro)) {
                                    echo '<p style="color: red;">' . $erro . '</p>';
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

        checkboxMostrarSenha.addEventListener('change', function() {
            const tipo = checkboxMostrarSenha.checked ? "text" : "password";
            campoSenha.type = tipo;
        });
    </script>
</body>
</html>
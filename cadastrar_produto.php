<?php
    include_once "sessao.php";

    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $preco_oferta = isset($_POST['preco_oferta']) && !empty($_POST['preco_oferta']) ? $_POST['preco_oferta'] : NULL;
    $id_loja = $_POST['id_loja'];
    $img_caminho = isset($_POST['img_caminho']) && !empty($_POST['img_caminho']) ? $_POST['img_caminho'] : "produto_default.png";
    $img_caminho = "imagens/produtos/" . $img_caminho;
    $descricao = $_POST['descricao'];

    // Verifica oferta
    $verifica_oferta = $preco_oferta ? '1' : '0';

    if($preco_oferta != null){
        $sqlInsert = "INSERT INTO PRODUTO (CODIGO, NOME, PRECO, PRECO_OFERTA, VERIFICA_OFERTA, ID_LOJA, IMG_CAMINHO, DESCRICAO) 
        VALUES ('$codigo', '$nome', '$preco', '$preco_oferta', '$verifica_oferta', '$id_loja', '$img_caminho', '$descricao')";
    }
    else{
        $sqlInsert = "INSERT INTO PRODUTO (CODIGO, NOME, PRECO, ID_LOJA, IMG_CAMINHO, DESCRICAO) 
        VALUES ('$codigo', '$nome', '$preco', '$id_loja', '$img_caminho', '$descricao')";
    }
    

    if (mysqli_query($conn, $sqlInsert)) {
        mysqli_close($conn);
        header("Location: adicionar_produto.php");
        exit;
    } else {
        echo "<script>alert('Erro ao adicionar produto: " . mysqli_error($conn) . "');</script>";
    }
?>

<?php

     require __DIR__.'/vendor/autoload.php';

use \App\Pix\Payload;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

$valorPedido = $_GET['valor'];
$nroPedido = $_GET['nroPedido'];

// Verifica se o valor é um número e maior que 0
if (!is_numeric($valorPedido) || $valorPedido <= 0) {
     die("Valor inválido para o pedido.");
}

// Formata o valor para 2 casas decimais
$valorFormatado = number_format((float)$valorPedido, 2, '.', '');

//INSTANCIA PRINCIPAL DO PAYLOAD PIX
$obPayload = (new Payload)->setPixKey('viniciusmaurer2122@gmail.com')//c512d486-b7be-4ca1-8a09-58fc905c0d2b
                         ->setDescription('Pagamento do pedido '.$nroPedido)
                         ->setMerchantName('VINICIUS MAURER')
                         ->setMerchantCity('BRASILIA')
                         ->setAmount($valorFormatado)
                         ->setTxid('SAMAP');

// CODIGO DE PAGAMENTO PIX
$payloadQrCode = $obPayload->getPayload();

// QR CODE
$obQrCode = new QrCode($payloadQrCode);

// IMAGEM DO QR CODE
$image = (new Output\Png)->output($obQrCode,400);

header('Content-Type: image/png');
echo $image;

?>
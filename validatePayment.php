<?php
require_once "IzipayController.php";
require_once "keys.php";

$izipay = new IzipayController();

// Firmar la respuesta de la transacción
$postBody = json_decode(file_get_contents("php://input"),true);

if( isset($postBody["hash"]) ){
    
    // Aquí va la logica de verificación de hash

    if($izipay->checkHash($postBody) )
    {
        file_put_contents("rpta.json",file_get_contents("php://input"));
        echo json_encode(array("rpta"=>"ok"));

    }else{
        echo json_encode(array("rpta"=>"error", "body"=>"Error de firma"));
    }

    
}else{
    echo json_encode(array("rpta"=>"error", "body"=> $postBody));
    file_put_contents("error.json",file_get_contents("php://input"));
}
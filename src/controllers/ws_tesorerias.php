<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
include_once ('src/funciones/igtech.funciones_tesorerias.php');

$app->get('/tesorerias/{empresa}',function(Request $request,Response $response){
    //$i_token=$request->getHeaderLine('Authorization');
    //$i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $w_empresa= $request->getAttribute('empresa');
    try{
        // $w_datos_token=Auth::GetData($i_token);
        // $w_autorizacion= seleccionarToken($i_token);
        // if($w_datos_token->respuesta ==0 and $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=listaTesorerias($w_empresa);
            
        // }else{
            // $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        // }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
   
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
    return $response;
});

$app->get('/tesorerias_compras/{empresa}',function(Request $request,Response $response){
    //$i_token=$request->getHeaderLine('Authorization');
    //$i_ct=$request->getHeaderLine('Content-Type');
    $i_accept=$request->getHeaderLine('Accept');
    $w_empresa= $request->getAttribute('empresa');
    try{
        // $w_datos_token=Auth::GetData($i_token);
        // $w_autorizacion= seleccionarToken($i_token);
        // if($w_datos_token->respuesta ==0 and $w_autorizacion['datos']['estado']=='V'){
            $o_respuesta=listaTesoreriasCompras($w_empresa);
            
        // }else{
            // $o_respuesta=array('error'=>'9998','mensaje'=>'token invalido');
        // }
    }catch (Throwable $e) {
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
   
    $response->getBody()->write(enviarRespuesta($i_accept,$o_respuesta));
    return $response;
});
?>
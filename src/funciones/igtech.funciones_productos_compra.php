<?php
function listaProductosCompra($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT        IDPRODCOM, NOMBRE
                         FROM            PRODUCTOS_COMPRAS
                         WHERE        (COD_EMPRESA = $i_empresa)";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            while($row = sqlsrv_fetch_object( $result)) { 
                $w_respuesta[] = array(
                    'IDPRODCOM'           =>  utf8_encode($row->IDPRODCOM),
                    'NOMBRE'      =>  utf8_encode($row->NOMBRE)
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        }
        sqlsrv_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function BuscaProductoCompra($i_producto){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT        ID_IMPUESTO, CUENTA
                     FROM        PRODUCTOS_COMPRAS
                    WHERE        (IDPRODCOM = $i_producto)";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            while($row = sqlsrv_fetch_object( $result)) { 
                $w_respuesta[] = array(
                    'ID_IMPUESTO'           =>  utf8_encode($row->ID_IMPUESTO),
                    'CUENTA'      =>  utf8_encode($row->CUENTA)
                );
            }
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        }
        sqlsrv_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

?>
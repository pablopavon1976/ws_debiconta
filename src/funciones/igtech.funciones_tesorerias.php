<?php
function listaTesorerias($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT IDTESORERIA, NOMBRE
                            FROM      TESORERIA
                            WHERE        (COD_EMPRESA = $i_empresa)
                            AND ID_PLAN_DE_CUENTA IS NOT NULL
                            and COMPRASVENTAS IN ('Ambos','Ventas') 
                            ORDER BY NOMBRE ";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql); 
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            while($row = sqlsrv_fetch_object( $result)) { 
                $w_respuesta[] = array(
                    'IDTESORERIA'           =>  utf8_encode($row->IDTESORERIA),
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

function listaTesoreriasCompras($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT IDTESORERIA, NOMBRE
                            FROM      TESORERIA
                            WHERE        (COD_EMPRESA = $i_empresa)
                            AND ID_PLAN_DE_CUENTA IS NOT NULL
                            and COMPRASVENTAS IN ('Ambos','Compras') 
                            ORDER BY NOMBRE ";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql); 
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            while($row = sqlsrv_fetch_object( $result)) { 
                $w_respuesta[] = array(
                    'IDTESORERIA'           =>  utf8_encode($row->IDTESORERIA),
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
?>
<?php
function listaEmpresas(){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT COD_EMPRESA,
                            NOMBRE_COMERCIAL,
                            IDENTIFICACION,
                            TIPO_DE_IDENTIFICACION
                     FROM EMPRESA";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            while($row = sqlsrv_fetch_object( $result)) { 
                $w_respuesta[] = array(
                    'COD_EMPRESA'           =>  utf8_encode($row->COD_EMPRESA),
                    'NOMBRE_COMERCIAL'      =>  utf8_encode($row->NOMBRE_COMERCIAL),
                    'IDENTIFICACION'        =>  utf8_encode($row->IDENTIFICACION),
                    'TIPO_DE_IDENTIFICACION'=>  utf8_encode($row->TIPO_DE_IDENTIFICACION)
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
<?php
function registrarToken($i_data,$estado){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar TOken ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $Log->EscribirLog(' Estado: '.$estado);

        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO sis_token(token, estado) 
                     VALUES('$i_data', '$estado')"; 
        $Log->EscribirLog(' Consulta: '.$insert_sql);
        if (!$result = sqlsrv_query($ws_conexion, $insert_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors()); 
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Token registrado','datos'=>$i_data);      
        }
        sqlsrv_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarToken($i_data,$estado){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Token ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $Log->EscribirLog(' Estado: '.$estado);

        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE sis_token 
                     SET  estado='".$estado."'
                     WHERE token='".$i_data."';";
        $Log->EscribirLog(' Consulta: '.$update_sql);
        if (!$result = sqlsrv_query($ws_conexion, $update_sql)){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Token Actualizado','datos'=>$i_data);        
        }
        sqlsrv_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function seleccionarToken($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Seleccionar Token ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        
        $select_sql="SELECT token, 
                            estado 
                     FROM sis_token
                     WHERE token='".$i_data."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        if($result = sqlsrv_query($ws_conexion, $select_sql)){
            $w_respuesta=array();
            $num_rows=0;
            while( $row = sqlsrv_fetch_object( $result)) {
               $num_rows+=1;
               $w_respuesta = array(	
                   'token' =>$row->token,
                   'estado'=>$row->estado,
               );
            }
            if($num_rows>0){
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);  
            }else{
                $w_respuesta=array('token'=>$i_data,'estado'=>'N');
                $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el token');
            }
        }else{
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }    
        //desconectamos la base de datos
        sqlsrv_close($ws_conexion) ;
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }    
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}
?>
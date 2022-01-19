<?php

include_once ('src/funciones/igtech.funciones_token.php');
function verificarLogin($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Verificar Login ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));        
        $w_usuario  = 	$i_data['user'];
        $w_password = 	$i_data['pass'];
        $ws_conexion=ws_coneccion_bdd();
        $select_sql=   "SELECT COD_USUARIO,
                                USUARIO,
                                NOMBRE,
                                CI,
                                EMAIL,
                                ESTADO
                        FROM USUARIOS 
                        WHERE USUARIO='".$w_usuario."'
                        AND CONTRASENIA='".$w_password."'";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);

        if($result === false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $num_rows=0;
            while( $devolver = sqlsrv_fetch_object( $result)) {
               $num_rows+=1;
               $w_respuesta=array('token'=>Auth::SignIn(['respuesta' => 0,'mensaje' => 'ok','usuario' => $devolver->NOMBRE]));
               $o_respuesta=array('error'=>'0','mensaje'=>'Login OK','datos'=>$w_respuesta);
               registrarToken($w_respuesta['token'],'V');
            }
            if($num_rows=0){    
                $o_respuesta=array('error'=>'9996','mensaje'=>'No existe el Usuario');
            }
        }    
        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    sqlsrv_close($ws_conexion);
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}



function logOut($i_data){
    $Log=new IgtechLog ();
    $Log->Abrir();
    $Log->EscribirLog(' LogOut ');
    $Log->EscribirLog(' DATOS DE ENTRADA');
    $Log->EscribirLog(' Datos: '.var_export($i_data,true));
    return actualizarToken($i_data,'N');
}

?>
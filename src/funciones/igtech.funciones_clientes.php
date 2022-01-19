<?php
function listaClientesEmpresa($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="   SELECT      COD_CLIENTE, 
                                    RUC, 
                                    NOMBRE
                        FROM       CLIENTES
                        WHERE      COD_EMPRESA = $i_empresa
                        ORDER BY NOMBRE";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            while($row = sqlsrv_fetch_object( $result)) { 
                $w_respuesta[] = array(
                    'cod_cliente' =>  utf8_encode($row->COD_CLIENTE),
                    'ruc' =>  utf8_encode($row->RUC),
                    'nombre' =>  utf8_encode($row->NOMBRE),
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

function BuscaClienteCedula($i_cedula, $i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT        COD_CLIENTE, 
                                   RUC, 
                                   NOMBRE
                        FROM       CLIENTES
                        WHERE      COD_EMPRESA = $i_empresa 
                        AND        RUC LIKE '$i_cedula%';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            $row = sqlsrv_fetch_object( $result); 
                $w_respuesta = array(
                    'cod_cliente'           =>  utf8_encode($row->COD_CLIENTE),
                    'ruc'      =>  utf8_encode($row->RUC),
                    'nombre'      =>  utf8_encode($row->NOMBRE)
                );
            
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        }
        sqlsrv_close($ws_conexion);
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarCliente($i_empresa,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Cliente');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO CLIENTES(
                            COD_EMPRESA,
                            tipo_identificacion,
                            RUC,
                            NOMBRE,
                            PROVINCIA,
                            CIUDAD,
                            DIRECCION,
                            TELEFONO,
                            EMAIL
                            )
                     VALUES(
                        ".$i_empresa.", 
                        '".$i_data['tipo_identificacion']."',
                        '".$i_data['identificacion']."',
                        '".$i_data['nombre']."',
                        '".$i_data['provincia']."',
                        '".$i_data['ciudad']."',
                        '".$i_data['direccion']."',
                        '".$i_data['telefono']."',
                        '".$i_data['email']."');";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        $result = sqlsrv_query($ws_conexion, $insert_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $select_sql="SELECT SCOPE_IDENTITY() AS [SCOPE_IDENTITY]";
            $result = sqlsrv_query($ws_conexion, $select_sql);
            if($result===false){
                $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
            }else{    
                $w_respuesta = array(); //creamos un array
                while($row = sqlsrv_fetch_object( $result)) { 
                    $w_respuesta = array(
                        'cod_cliente'   =>  utf8_encode($row->SCOPE_IDENTITY),
                        'ruc'           =>  $i_data['identificacion'],
                        'nombre'        =>  $i_data['nombre'],
                    );
                }
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function actualizarCliente($i_empresa,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Cliente');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="UPDATE CLIENTES SET 
                        tipo_identificacion='".$i_data['tipo_identificacion']."',
                        NOMBRE='".$i_data['nombre']."',
                        PROVINCIA='".$i_data['provincia']."',
                        CIUDAD='".$i_data['ciudad']."',
                        DIRECCION='".$i_data['direccion']."',
                        TELEFONO='".$i_data['telefono']."',
                        EMAIL='".$i_data['email']."'
                    WHERE COD_EMPRESA=".$i_empresa."
                    AND RUC='".$i_data['identificacion']."';";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        $result = sqlsrv_query($ws_conexion, $insert_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $o_respuesta=BuscaClienteCedula($i_data['identificacion'],$i_empresa);
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function inserUpdateCliente($i_empresa,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Cliente ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosCliente($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) as count
                     FROM  CLIENTES
                     WHERE RUC='".$i_data['identificacion']."'
                     AND COD_EMPRESA=".$i_empresa.";";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if ($result=== false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $row = sqlsrv_fetch_object( $result); 
            if($row->count==0){
                $o_respuesta=registrarCliente($i_empresa,$i_data);    
            }else{
                $o_respuesta=actualizarCliente($i_empresa,$i_data);    
            }
        } 
        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }

    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;  
}

function validarDatosCliente($i_data){
    
    if (!isset($i_data['tipo_identificacion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_identificacion');
        return $o_respuesta;
    }
    if (!isset($i_data['identificacion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo identificacion');
        return $o_respuesta;
    }
    if (!isset($i_data['nombre'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo nombre');
        return $o_respuesta;
    }
    if (!isset($i_data['provincia'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo provincia');
        return $o_respuesta;
    }
    if (!isset($i_data['ciudad'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo ciudad');
        return $o_respuesta;
    }
    if (!isset($i_data['direccion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo direccion');
        return $o_respuesta;
    }
    if (!isset($i_data['telefono'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo telefono');
        return $o_respuesta;
    }
    if (!isset($i_data['email'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo email');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}


?>
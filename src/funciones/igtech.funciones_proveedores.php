<?php
function listaProveedoresEmpresa($i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="   SELECT      IDCEDULARUC, 
                                    NUMEROIDENT, 
                                    NOMBRE
                        FROM        PROVEEDORES
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
                    'IDCEDULARUC' =>  utf8_encode($row->IDCEDULARUC),
                    'NUMEROIDENT' =>  utf8_encode($row->NUMEROIDENT),
                    'NOMBRE' =>  utf8_encode($row->NOMBRE),
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

function BuscaProveedorCedula($i_cedula, $i_empresa){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' LISTA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="  SELECT      IDCEDULARUC, 
                                    NUMEROIDENT, 
                                    NOMBRE
                        FROM        PROVEEDORES
                        WHERE      COD_EMPRESA = $i_empresa 
                        AND        NUMEROIDENT LIKE '$i_cedula%';";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{    
            $w_respuesta = array(); //creamos un array
            $row = sqlsrv_fetch_object( $result); 
                $w_respuesta = array(
                    'IDCEDULARUC'      =>  utf8_encode($row->IDCEDULARUC),
                    'NUMEROIDENT'      =>  utf8_encode($row->NUMEROIDENT),
                    'NOMBRE'           =>  utf8_encode($row->NOMBRE)
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

function registrarProveedor($i_empresa,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Proveedor');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO PROVEEDORES(
                            NUMEROIDENT, 
                            COD_EMPRESA, 
                            NOMBRE, 
                            TIPOID, 
                            DIRECCION, 
                            TELEFONO, 
                            DOCUMENTO, 
                            AUTORIZACION, 
                            SERIE, 
                            RANGO_DESDE, 
                            RANGO_HASTA, 
                            CADUCA, 
                            PORCENTAJE_IMPUESTO_RETENCION_IVA, 
                            email, 
                            SOCIO
                            )
                     VALUES(
                        '".$i_data['NUMEROIDENT']."', 
                        ".$i_empresa.", 
                        '".$i_data['NOMBRE']."',
                        '".$i_data['TIPOID']."',
                        '".$i_data['DIRECCION']."',
                        '".$i_data['TELEFONO']."',
                        '".$i_data['DOCUMENTO']."',
                        '".$i_data['AUTORIZACION']."',
                        '".$i_data['SERIE']."',
                        '".$i_data['RANGO_DESDE']."',
                        '".$i_data['RANGO_HASTA']."',
                        '".$i_data['CADUCA']."',
                        '".$i_data['PORCENTAJE_IMPUESTO_RETENCION_IVA']."',
                        '".$i_data['email']."',
                        '".$i_data['SOCIO']."');";  
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
                        'IDCEDULARUC'   =>  utf8_encode($row->SCOPE_IDENTITY),
                        'NUMEROIDENT'   =>  $i_data['NUMEROIDENT'],
                        'NOMBRE'        =>  $i_data['NOMBRE'],
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

function actualizarProveedor($i_empresa,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Actualizar Proveedor');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="UPDATE PROVEEDORES SET 
                        TIPOID='".$i_data['TIPOID']."',
                        NOMBRE='".$i_data['NOMBRE']."',
                        DIRECCION='".$i_data['DIRECCION']."',
                        TELEFONO='".$i_data['TELEFONO']."',
                        email='".$i_data['email']."'
                    WHERE COD_EMPRESA=".$i_empresa."
                    AND NUMEROIDENT='".$i_data['NUMEROIDENT']."';";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        $result = sqlsrv_query($ws_conexion, $insert_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $o_respuesta=BuscaProveedorCedula($i_data['NUMEROIDENT'],$i_empresa);
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function inserUpdateProveedor($i_empresa,$i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Insert Update Proveedor ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $w_validacion=validarDatosProveedor($i_data);
        if($w_validacion['error']<>'0'){
            return $w_validacion;
        }
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT count(*) as count
                     FROM  PROVEEDORES
                     WHERE NUMEROIDENT='".$i_data['NUMEROIDENT']."'
                     AND COD_EMPRESA=".$i_empresa.";";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result = sqlsrv_query($ws_conexion, $select_sql);
        if ($result=== false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $row = sqlsrv_fetch_object( $result); 
            if($row->count==0){
                $o_respuesta=registrarProveedor($i_empresa,$i_data);    
            }else{
                $o_respuesta=actualizarProveedor($i_empresa,$i_data);    
            }
        } 
        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }

    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;  
}

function validarDatosProveedor($i_data){
    
    if (!isset($i_data['TIPOID'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo TIPOID');
        return $o_respuesta;
    }
    if (!isset($i_data['NUMEROIDENT'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo NUMEROIDENT');
        return $o_respuesta;
    }
    if (!isset($i_data['NOMBRE'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo NOMBRE');
        return $o_respuesta;
    }
    if (!isset($i_data['DIRECCION'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo DIRECCION');
        return $o_respuesta;
    }
    if (!isset($i_data['TELEFONO'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo TELEFONO');
        return $o_respuesta;
    }
    if (!isset($i_data['DOCUMENTO'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo DOCUMENTO');
        return $o_respuesta;
    }
    if (!isset($i_data['AUTORIZACION'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo AUTORIZACION');
        return $o_respuesta;
    }
    if (!isset($i_data['SERIE'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo SERIE');
        return $o_respuesta;
    }
    if (!isset($i_data['RANGO_DESDE'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo RANGO_DESDE');
        return $o_respuesta;
    }
    if (!isset($i_data['RANGO_HASTA'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo RANGO_HASTA');
        return $o_respuesta;
    }
    if (!isset($i_data['CADUCA'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo CADUCA');
        return $o_respuesta;
    }

    if (!isset($i_data['PORCENTAJE_IMPUESTO_RETENCION_IVA'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo PORCENTAJE_IMPUESTO_RETENCION_IVA');
        return $o_respuesta;
    }
    if (!isset($i_data['email'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo email');
        return $o_respuesta;
    }
    if (!isset($i_data['SOCIO'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo SOCIO');
        return $o_respuesta;
    }

    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}


?>
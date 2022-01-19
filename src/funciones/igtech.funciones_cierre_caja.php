<?php

function registrarCierreCaja($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' REGISTRAR Cierre de Caja');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' DATOS :'.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $w_asiento='';
        $select_sql="execute sp_genera_asiento_cierrecaja   @i_empresa=?,
                                                            @i_fecha=?,
                                                            @i_serie=?,
                                                            @i_total=?,
                                                            @o_codigo_asiento=?,
                                                            @o_centro_costos=?";
        $w_codigo_asiento='';
        $w_centro_costos=0;
        $procedure_params = array(
            &$i_data['empresa'],
            &$i_data['fecha'],
            &$i_data['serie'],
            &$i_data['total'],
            array(&$w_codigo_asiento, SQLSRV_PARAM_OUT),
            array(&$w_centro_costos, SQLSRV_PARAM_OUT)
        );

        $exec_sql=sqlsrv_prepare($ws_conexion,$select_sql,$procedure_params);
                
        if(sqlsrv_execute($exec_sql)){
            $w_respuesta=array(
                'id_asiento'=>$w_codigo_asiento,
                'centro_costos'=>$w_centro_costos
            );
            $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
        } else{
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }  
        
        sqlsrv_close($ws_conexion);
        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarDetalleCierreCaja($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Detalle Cierre de Caja');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO DETALLE_ASIENTO(
                            ID_ASIENTO,
                            ID_PLAN_DE_CUENTA,
                            DEBE_HABER,
                            VALOR,
                            CONCEPTO,
                            IDCENTRALCOSTOS)
                     SELECT 
                            '".$i_data['ID_ASIENTO']."',
                             ID_PLAN_DE_CUENTA,
                            '".$i_data['DEBE_HABER']."',  
                             ".$i_data['VALOR'].",
                            '".$i_data['CONCEPTO']."',
                             ".$i_data['IDCENTRALCOSTOS']."
                    FROM TESORERIA 
                    WHERE IDTESORERIA=".$i_data['IDTESORERIA'].";";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        $result = sqlsrv_query($ws_conexion, $insert_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Detalle Registrado','datos'=>$i_data);   
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}


function registrarNuevoCierreCaja($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' REGISTRAR CierreCaja ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' DATOS :'.var_export($i_data,true));
        //validamos que lleguen todos los datos
        $w_validar=validarDatosCierreCaja($i_data);
        if($w_validar['error']<>'0'){
            return $w_validar;
        }
        $w_asiento=registrarCierreCaja($i_data);
        if($w_asiento['error']<>'0'){
            return $w_asiento;
        }
        $w_tesorerias=$i_data['tesorerias'];
        foreach ($w_tesorerias as $tesoreria) {
            
            $w_detalle=array(
                'IDTESORERIA'=>$tesoreria['IDTESORERIA'],
                'ID_ASIENTO'=>$w_asiento['datos']['id_asiento'],
                'DEBE_HABER'=>'1',
                'VALOR'=>$tesoreria['valor'],
                'CONCEPTO'=>'',
                'IDCENTRALCOSTOS'=> $w_asiento['datos']['centro_costos'],
            );
            $w_resdetalle=registrarDetalleCierreCaja($w_detalle);
            if($w_resdetalle['error']<>'0'){
                return $w_resdetalle;
            }
        }
        $w_respuesta=array(
            'id_asiento'=>$w_asiento['datos']['id_asiento'],
        );
        $o_respuesta=array('error'=>'0','mensaje'=>'Cierre de Caja registrado exitosamente','datos'=>$w_respuesta); 

    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosCierreCaja($i_data){
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['fecha'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo fecha');
        return $o_respuesta;
    }
    if (!isset($i_data['serie'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo serie');
        return $o_respuesta;
    }
    if (!isset($i_data['total'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo total');
        return $o_respuesta;
    }

    if (!isset($i_data['tesorerias'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tesorerias');
        return $o_respuesta;
    }
    $suma_valores=0;
    $w_tesorerias=$i_data['tesorerias'];
    foreach ($w_tesorerias as $tesoreria) {
        $w_valida_tesoreria=validarDatosTesoreria($tesoreria);
        if($w_valida_tesoreria['error']<>'0'){
            return $w_valida_tesoreria;
        }else{
            $suma_valores+= $tesoreria['valor'];
        }
    }
    if($suma_valores<>$i_data['total']){
        $o_respuesta=array('error'=>'9999','mensaje'=>'El total del cierre de caja no coincide con la sumatoria de las tesorerias');
        return $o_respuesta;

    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function validarDatosTesoreria($i_data){
    if (!isset($i_data['IDTESORERIA'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo IDTESORERIA');
        return $o_respuesta;
    }
    if (!isset($i_data['valor'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo valor');
        return $o_respuesta;
    }
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}
?>
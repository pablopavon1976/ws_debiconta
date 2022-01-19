<?php
include_once('src/funciones/igtech.funciones_clientes.php');

function registrarNCVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar NC Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO FACTURA_VENTA (
                        COD_EMPRESA,
                        COD_CLIENTE,
                        TIPO_DOCUMENTO,
                        TIPOEMISION,
                        FECHA_VENTA,
                        FECHA_CADUCIDAD,
                        SERIE,
                        NUMERO_FACTURA,
                        AUTORIZACION,
                        AUTORIZACION_SRI,
                        OBSERVACIONES,
                        FIRMADO,
                        envio_correo,
                        SUBTOTAL,
                        DESCUENTO,
                        ICE,
                        BASEIVACERO,
                        BASEIVADOCE,
                        IVA,
                        TOTAL,
                        IDTESORERIA,
                        PLAZO,
                        TIEMPO, 
                        ESTADO,
                        RETUVIERON,
                        ACTIVOFIJO,
                        TOTALRETENIDO,
                        DIEZPORSERVICIOS,
                        NOGRABA,
                        FACTURA_AFECTA
                     ) VALUES (
                         ".$i_data['empresa'].",
                         ".$i_data['cod_cliente'].",
                        '".$i_data['tipo_documento']."',
                        '".$i_data['tipo_emision']."',
                        '".$i_data['fecha']."',
                        '".$i_data['fecha_caducidad']."',
                        '".$i_data['serie']."',
                        '".$i_data['secuencial']."',
                        '".$i_data['autorizacion']."',
                        '".$i_data['estado_sri']."',
                        '".$i_data['observaciones']."',
                        '".$i_data['firmado']."',
                        '".$i_data['envio_correo']."',
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                         ".$i_data['id_tesoreria'].",
                         ".$i_data['plazo'].",
                        '".$i_data['tiempo']."', 
                        'A',
                        'NO',
                        'NO',
                        0,
                        ".$i_data['servicio'].",
                        'False',
                        ".$i_data['factura_afecta']."
                     )";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        $result = sqlsrv_query($ws_conexion, $insert_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $select_sql="SELECT SCOPE_IDENTITY() AS SCOPE_IDENTITY";
            $result = sqlsrv_query($ws_conexion, $select_sql);
            if($result===false){
                $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
            }else{    
                $w_respuesta = array(); //creamos un array
                while($row = sqlsrv_fetch_object( $result)) { 
                    $w_respuesta = array(
                        'id_nc' =>  utf8_encode($row->SCOPE_IDENTITY)
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

function registrarDetalleNCVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Detalle NC Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO detalle_factura_venta (
                            ID_FACTURA_VENTA,
                            ID_GRUPOS_PRODUCTOS_VENTAS,
                            ID_IMPUESTO,
                            CANTIDAD,
                            PVP,
                            DESCUENTO,
                            SUBTOTAL,
                            POR_ICE,
                            ICE,
                            BASE_IMPONIBLE_CERO,
                            BASE_IMPONIBLE_DOCE,
                            PORCENTAJE_IVA,
                            IVA,
                            TOTAL,
                            CUENTA,
                            COD_PRINCIPAL,
                            COD_AUXILIAR,
                            detalle,
                            CODIGO_PROD,
                            IVA_POR 
                            ) 
                    SELECT 
                            ".$i_data['id_nc'].",
                            ID_GRUPOS_PRODUCTOS_VENTAS,
                            ID_IMPUESTO,
                            ".$i_data['cantidad'].",
                            ".$i_data['pvp'].",
                            ".(-1)*$i_data['descuento'].",
                            ".(-1)*$i_data['subtotal'].",
                            POR_ICE,
                            ".(-1)*$i_data['ice'].",
                            ".(-1)*$i_data['base_cero'].", 
                            ".(-1)*$i_data['base_doce'].",
                            IVA ,
                            ".(-1)*$i_data['iva'].",
                            ".(-1)*$i_data['total'].",
                            CUENTA ,
                            COD_PRINCIPAL,
                            COD_AUXILIAR,
                            '',
                            NULL ,
                            '' 
                        FROM GRUPO_PRODUCTO_VENTA
                        WHERE ID_GRUPOS_PRODUCTOS_VENTAS=".$i_data['cod_producto_debi'].";";  
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

function actualizaTotalesNC($i_nota_credito){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Detalle NC Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_nota_credito,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE FACTURA_VENTA  
                        SET	 
                            SUBTOTAL = T.SUBTOTAL ,
                            DESCUENTO= T.DESCUENTO,
                            ICE= T.ICE,
                            BASEIVACERO= T.BASE_IMPONIBLE_CERO,
                            BASEIVADOCE= T.BASE_IMPONIBLE_DOCE,
                            IVA= T.IVA,
                            TOTAL= (T.TOTAL) + DIEZPORSERVICIOS
                        FROM V_TOTALES_FACTURA_VENTA T
                        WHERE FACTURA_VENTA.ID_FACTURA_VENTA = T.ID_FACTURA_VENTA
                        AND FACTURA_VENTA.ID_FACTURA_VENTA=".$i_nota_credito.";";   
        $Log->EscribirLog(' Consulta: '.$update_sql);    
        $result = sqlsrv_query($ws_conexion, $update_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Saldos Actualizados','datos'=>$i_nota_credito);   
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function generarAsientoNC($i_nota_credito){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' generarAsientoFacrura ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_nota_credito,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="execute sp_genera_asiento_nc ".$i_nota_credito;  
        $Log->EscribirLog(' Consulta: '.$update_sql);    
        $result = sqlsrv_query($ws_conexion, $update_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $select_sql="SELECT ID_ASIENTO FROM FACTURA_VENTA WHERE ID_FACTURA_VENTA=".$i_nota_credito;  
            $Log->EscribirLog(' Consulta: '.$select_sql);    
            $result = sqlsrv_query($ws_conexion, $select_sql);
            if ($result === false ){
                $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
            }else{
                $row= sqlsrv_fetch_object($result);
            $w_respuesta = array(
                'id_asiento' =>$row->ID_ASIENTO,
            );
            $o_respuesta=array('error'=>'0','mensaje'=>'Saldos Actualizados','datos'=>$w_respuesta); 
        }  
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function seleccionaFactura ($i_empresa, $i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' SELECCIONAR FACTURA ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $select_sql="SELECT ID_FACTURA_VENTA 
                        FROM FACTURA_VENTA 
                        where COD_EMPRESA= $i_empresa
                        AND SERIE='".$i_data['serie']."'
                        AND NUMERO_FACTURA='".$i_data['secuencial']."'
                        AND TIPO_DOCUMENTO in ('18','01')";
        $Log->EscribirLog(' Consulta: '.$select_sql);
        $result=sqlsrv_query($ws_conexion, $select_sql);
        if($result===false){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{ 
            $w_respuesta = array(); //creamos un array
            while($row = sqlsrv_fetch_object( $result)) { 
                $w_respuesta = array(
                    'id_factura'=>$row->ID_FACTURA_VENTA
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

function registrarNuevaNC($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' REGISTRAR Nueva NC Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' DATOS :'.var_export($i_data,true));
        //validamos que lleguen todos los datos
        $w_validar=validarDatosNuevaNC($i_data);
        if($w_validar['error']<>'0'){
            return $w_validar;
        }
        //registramos el cliente
        $w_empresa=$i_data['empresa'];
        $w_cliente=$i_data['cliente'];
        $w_datos_cliente=inserUpdateCliente($w_empresa,$w_cliente);
        if($w_datos_cliente['error']<>'0'){
            return $w_datos_cliente;
        }
        $w_factura_afecta=seleccionaFactura($w_empresa,$i_data['factura_afecta']);
        if($w_factura_afecta['error']<>'0'){
            return $w_factura_afecta;
        }

        //registramos la factura_venta
        if ($i_data['estado_sri']=='AUTORIZADO'){
            $w_envio_correo='true';
            $w_firmado='true';
        }else{
            $w_envio_correo='false';
            $w_firmado='false';
        }
        $w_nota_credito=array(
            'empresa'=>         $i_data['empresa'],
            'cod_cliente'=>     $w_datos_cliente['datos']['cod_cliente'],
            'tipo_documento'=>  $i_data['tipo_documento'],
            'tipo_emision'=>    $i_data['tipo_emision'],
            'fecha'=>           $i_data['fecha'],
            'fecha_caducidad'=> $i_data['fecha_caducidad'],
            'serie'=>           $i_data['serie'],
            'secuencial'=>      $i_data['secuencial'],
            'autorizacion'=>    $i_data['autorizacion'],
            'estado_sri'=>      $i_data['estado_sri'],
            'observaciones'=>   $i_data['observaciones'],
            'firmado'=>         $w_firmado,
            'envio_correo'=>    $w_envio_correo,
            'id_tesoreria'=>    $i_data['id_tesoreria'],
            'plazo'=>           $i_data['plazo'],
            'tiempo'=>          $i_data['tiempo'],
            'servicio'=>        $i_data['servicio']*(-1),
            'factura_afecta'=>  $w_factura_afecta['datos']['id_factura'],
        );
        $w_res_nota_credito=registrarNCVenta($w_nota_credito);
        if ($w_res_nota_credito['error']<>'0'){
            return $w_res_nota_credito;
        }
        //registramos los detalles
        $w_productos=$i_data['productos'];
        foreach ($w_productos as $producto) {
            $w_base_cero=0;
            $w_base_doce=0;
            $w_subtotal=$producto['cantidad']*$producto['pvp'];
            if ($producto['porcentaje_iva']==0){
                $w_base_cero=$w_subtotal-$producto['descuento_total'];
                $w_valor_iva=0;
                $w_total=$w_base_cero;
            }else{
                $w_base_doce=$w_subtotal-$producto['descuento_total'];
                $w_valor_iva=round($w_base_doce*$producto['porcentaje_iva']/100,2);
                $w_total=$w_base_doce+$w_valor_iva;
            }
            $w_producto=array(
                'id_nc'=>$w_res_nota_credito['datos']['id_nc'],
                'cod_producto_debi'=>$producto['cod_producto_debi'],
                'cantidad'=>$producto['cantidad'],
                'pvp'=>$producto['pvp'],
                'descuento'=>$producto['descuento_total'],
                'subtotal'=>$w_subtotal,
                'ice'=>0,
                'base_cero'=>$w_base_cero,
                'base_doce'=>$w_base_doce,
                'iva'=>$w_valor_iva,
                'total'=>$w_total
            );
            $w_detalle=registrarDetalleNCVenta($w_producto);
            if($w_detalle['error']<>'0'){
                return $w_detalle;
            }
        }
        $w_actualiza_saldos=actualizaTotalesNC($w_res_nota_credito['datos']['id_nc']);
        if ($w_actualiza_saldos['error']<>'0'){
            return $w_actualiza_saldos;
        }

        //generamos el asiento
        $w_genera_asiento=generarAsientoNC($w_res_nota_credito['datos']['id_nc']);
        if($w_genera_asiento['error']<>'0'){
            return $w_genera_asiento;
        }else{
            $w_respuesta=array(
                'id_nota_credito'=>$w_res_nota_credito['datos']['id_nc'],
                'id_asiento'=>$w_genera_asiento['datos']['id_asiento'],
            );    
        }
        
        $o_respuesta=array('error'=>'0','mensaje'=>'Nota de Credito Registrada','datos'=>$w_respuesta); 

    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosNuevaNC($i_data){
    if (!isset($i_data['empresa'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo empresa');
        return $o_respuesta;
    }
    if (!isset($i_data['tipo_documento'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_documento');
        return $o_respuesta;
    }
    if (!isset($i_data['tipo_emision'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tipo_emision');
        return $o_respuesta;
    }
    if (!isset($i_data['fecha'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo fecha');
        return $o_respuesta;
    }
    if (!isset($i_data['fecha_caducidad'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo fecha_caducidad');
        return $o_respuesta;
    }
    if (!isset($i_data['serie'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo serie');
        return $o_respuesta;
    }
    if (!isset($i_data['secuencial'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo secuencial');
        return $o_respuesta;
    }
    if (!isset($i_data['autorizacion'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo autorizacion');
        return $o_respuesta;
    }
    if (!isset($i_data['estado_sri'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo estado_sri');
        return $o_respuesta;
    }
    if (!isset($i_data['observaciones'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo observaciones');
        return $o_respuesta;
    }
    if (!isset($i_data['id_tesoreria'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo id_tesoreria');
        return $o_respuesta;
    }
    if (!isset($i_data['plazo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo plazo');
        return $o_respuesta;
    }
    if (!isset($i_data['tiempo'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo tiempo');
        return $o_respuesta;
    }
    if (!isset($i_data['cliente'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo cliente');
        return $o_respuesta;
    }
    if (!isset($i_data['productos'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el campo productos');
        return $o_respuesta;
    }

    if (!isset($i_data['factura_afecta'])){
        $o_respuesta=array('error'=>'9999','mensaje'=>'falta el id de la factura a la que afecta');
        return $o_respuesta;
    }

    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

?>
<?php
include_once('src/funciones/igtech.funciones_clientes.php');

function registrarVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();

       if($i_data['tipo_documento']=='01'){
           $i_data['tipo_documento']='18';
        }
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
                        PLAZO,
                        TIEMPO, 
                        ESTADO,
                        RETUVIERON,
                        ACTIVOFIJO,
                        TOTALRETENIDO,
                        DIEZPORSERVICIOS,
                        NOGRABA,
                        IDTESORERIA
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
                         ".$i_data['plazo'].",
                        '".$i_data['tiempo']."', 
                        'A',
                        'NO',
                        'NO',
                        0,
                        ".$i_data['servicio'].",
                        'False',
                        ".$i_data['tesoreria']."
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
                        'id_factura' =>  utf8_encode($row->SCOPE_IDENTITY)
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

function registrarDetalleVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Detalle Venta ');
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
                            ".$i_data['id_factura'].",
                            ID_GRUPOS_PRODUCTOS_VENTAS,
                            ID_IMPUESTO,
                            ".$i_data['cantidad'].",
                            ".$i_data['pvp'].",
                            ".$i_data['descuento'].",
                            ".$i_data['subtotal'].",
                            POR_ICE,
                            ".$i_data['ice'].",
                            ".$i_data['base_cero'].", 
                            ".$i_data['base_doce'].",
                            IVA ,
                            ".$i_data['iva'].",
                            ".$i_data['total'].",
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

function actualizaTotalesFactura($i_factura){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Detalle Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_factura,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="UPDATE FACTURA_VENTA  
                        SET	 
                            SUBTOTAL = T.SUBTOTAL ,
                            DESCUENTO= T.DESCUENTO,
                            ICE= T.ICE,
                            BASEIVACERO= T.BASE_IMPONIBLE_CERO,
                            BASEIVADOCE= T.BASE_IMPONIBLE_DOCE,
                            IVA= T.IVA,
                            TOTAL= T.TOTAL + DIEZPORSERVICIOS
                        FROM V_TOTALES_FACTURA_VENTA T
                        WHERE FACTURA_VENTA.ID_FACTURA_VENTA = T.ID_FACTURA_VENTA
                        AND FACTURA_VENTA.ID_FACTURA_VENTA=".$i_factura.";";  
        $Log->EscribirLog(' Consulta: '.$update_sql);    
        $result = sqlsrv_query($ws_conexion, $update_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Saldos Actualizados','datos'=>$i_factura);   
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function generarAsientoFactura($i_factura){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' generarAsientoFacrura ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_factura,true));
        $ws_conexion=ws_coneccion_bdd();
        $update_sql="execute sp_genera_asiento_factura ".$i_factura;  
        $Log->EscribirLog(' Consulta: '.$update_sql);    
        $result = sqlsrv_query($ws_conexion, $update_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $select_sql="SELECT ID_ASIENTO FROM FACTURA_VENTA WHERE ID_FACTURA_VENTA=".$i_factura;  
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

function registrarNuevaVenta($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' REGISTRAR NuevaVenta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' DATOS :'.var_export($i_data,true));
        //validamos que lleguen todos los datos
        $w_validar=validarDatosNuevaFactura($i_data);
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

        //registramos la factura_venta
        if ($i_data['estado_sri']=='AUTORIZADO'){
            $w_envio_correo='true';
            $w_firmado='true';
        }else{
            $w_envio_correo='false';
            $w_firmado='false';
        }
        $w_formas_pago=$i_data['formas_pago'];
        foreach ($w_formas_pago as $forma_pago) {
            $w_tesoria=$forma_pago['id_tesoreria'];
        }    
        $w_factura_venta=array(
            'empresa'=>$i_data['empresa'],
            'cod_cliente'=>$w_datos_cliente['datos']['cod_cliente'],
            'tipo_documento'=>$i_data['tipo_documento'],
            'tipo_emision'=>$i_data['tipo_emision'],
            'fecha'=>$i_data['fecha'],
            'fecha_caducidad'=>$i_data['fecha_caducidad'],
            'serie'=>$i_data['serie'],
            'secuencial'=>$i_data['secuencial'],
            'autorizacion'=>$i_data['autorizacion'],
            'estado_sri'=>$i_data['estado_sri'],
            'observaciones'=>$i_data['observaciones'],
            'firmado'=>$w_firmado,
            'envio_correo'=>$w_envio_correo,
            'plazo'=>$i_data['plazo'],
            'tiempo'=>$i_data['tiempo'],
            'servicio'=>$i_data['servicio'],
            'tesoreria'=>$w_tesoria,
        );
        $w_res_factura_venta=registrarVenta($w_factura_venta);
        if ($w_res_factura_venta['error']<>'0'){
            return $w_res_factura_venta;
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
                'id_factura'=>$w_res_factura_venta['datos']['id_factura'],
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
            $w_detalle=registrarDetalleVenta($w_producto);
            if($w_detalle['error']<>'0'){
                return $w_detalle;
            }
        }
        $w_actualiza_saldos=actualizaTotalesFactura($w_res_factura_venta['datos']['id_factura']);
        if ($w_actualiza_saldos['error']<>'0'){
            return $w_actualiza_saldos;
        }

        $w_formas_pago=$i_data['formas_pago'];
        foreach ($w_formas_pago as $forma_pago) {
            $w_forma_pago=array(
                'id_tesoreria'=>$forma_pago['id_tesoreria'],
                'fecha'=>$i_data['fecha'],
                'compra_Venta'=>'Ventas',
                'id_factura'=>$w_res_factura_venta['datos']['id_factura'],
                'tipo_pago'=>$forma_pago['tipo_pago'],
                'documento'=>$forma_pago['documento'],
                'lote'=>$forma_pago['lote'],
                'valor'=>$forma_pago['valor'],
                'conciliado'=>'False',  
            );
            $w_detalle=registrarDetallePago($w_forma_pago);
            if($w_detalle['error']<>'0'){
                return $w_detalle;
            }    
        }    

        //generamos el asiento
        $w_genera_asiento=generarAsientoFactura($w_res_factura_venta['datos']['id_factura']);
        if($w_genera_asiento['error']<>'0'){
            return $w_genera_asiento;
        }else{
            $w_respuesta=array(
                'id_factura'=>$w_res_factura_venta['datos']['id_factura'],
                'id_asiento'=>$w_genera_asiento['datos']['id_asiento'],
            );    
        }
        
        $o_respuesta=array('error'=>'0','mensaje'=>'Factura Registrado','datos'=>$w_respuesta); 

    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function validarDatosNuevaFactura($i_data){
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
    if (!isset($i_data['formas_pago'])){
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
    $o_respuesta=array('error'=>'0','mensaje'=>'ok');
    return $o_respuesta;
}

function registrarDetallePago($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Detalle Pago ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="INSERT INTO DETALLE_MOVIENTOSX(
                            IDTESORERIA,
                            FECHA,
                            COMPRA_VENTA,
                            CODIGO_FACTURAS,
                            TIPO_PAGO_TARJETA,
                            NUM_DOCUMENTO,
                            LOTE,
                            TOTALFACT,
                            CONCILIADO) 
                    VALUES(
                         ".$i_data['id_tesoreria'].",
                        '".$i_data['fecha']."',
                        '".$i_data['compra_Venta']."',
                         ".$i_data['id_factura'].",
                        '".$i_data['tipo_pago']."',
                        '".$i_data['documento']."',
                        '".$i_data['lote']."',
                         ".$i_data['valor'].",
                        '".$i_data['conciliado']."' 
                    )";  
        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        $result = sqlsrv_query($ws_conexion, $insert_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            $o_respuesta=array('error'=>'0','mensaje'=>'Pago Registrado','datos'=>$i_data);   
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}


?>
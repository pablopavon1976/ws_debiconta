<?php
function registrarCompra($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="insert into factura_compra (
                COD_EMPRESA, 
                IDTESORERIA, 
                IDCEDULARUC, 
                IDENTIFICACION_FISCAL, 
                SERIE, 
                NUMERO_FACTURA, 
                TIPO_COMPROBANTE, 
                INVENTARIO, 
                FECHA_RECEPCION, 
                FECHA_FACTURA, 
                FECHA_CADUCA, 
                AUTORIZACION_FACTURA,
                CREDITO_TRIBUTARIO, 
                HAGO_RETENCION, 
                OBSERVACION, 
                SUBTOTAL, 
                MONTO_IVA, 
                MONTO_RENTA, 
                TOTAL_APAGAR, 
                ESTADO, 
                SUSTENTOTRIBUTARIO, 
                TOTAL_IVA, 
                TOTAL_RETENIDO, 
                ICE, 
                NOGRABA, 
                RETENCION_ASUMIDA, 
                SERVICIOS,
                SERIERETENCION, 
                SECUENCIARETENCION, 
                AUTORIZACIONRETENCION, 
                FECHAEMISONRETENCION, 
                FECHACADUCARETENCION, 
                FIRMADO, 
                envio_correo, 
                TIPOEMISION) values (
                    ".$i_data['IDTESORERIA'].", 
                    ".$i_data['IDCEDULARUC'].", 
                    ".$i_data['COD_EMPRESA'].", 
                    ".$i_data['IDENTIFICACION_FISCAL'].", 
                    '".$i_data['SERIE']."', 
                    '".$i_data['NUMERO_FACTURA']."', 
                    '".$i_data['TIPO_COMPROBANTE']."', 
                    '".$i_data['INVENTARIO']."', 
                    '".$i_data['FECHA_RECEPCION']."', 
                    '".$i_data['FECHA_FACTURA']."', 
                    '".$i_data['FECHA_CADUCA']."', 
                    '".$i_data['AUTORIZACION_FACTURA']."',
                    '".$i_data['CREDITO_TRIBUTARIO']."', 
                    '".$i_data['HAGO_RETENCION']."', 
                    '".$i_data['OBSERVACION']."', 
                    ".$i_data['SUBTOTAL'].", 
                    ".$i_data['MONTO_IVA'].", 
                    ".$i_data['MONTO_RENTA'].", 
                    ".$i_data['TOTAL_APAGAR'].", 
                    '".$i_data['ESTADO']."', 
                    '".$i_data['SUSTENTOTRIBUTARIO']."', 
                    ".$i_data['TOTAL_IVA'].", 
                    ".$i_data['TOTAL_RETENIDO'].", 
                    ".$i_data['ICE'].", 
                    '".$i_data['NOGRABA']."', 
                    '".$i_data['RETENCION_ASUMIDA']."', 
                    ".$i_data['SERVICIOS'].",
                    '".$i_data['SERIERETENCION']."', 
                    '".$i_data['SECUENCIARETENCION']."', 
                    '".$i_data['AUTORIZACIONRETENCION']."', 
                    '".$i_data['FECHAEMISONRETENCION']."', 
                    '".$i_data['FECHACADUCARETENCION']."', 
                    '".$i_data['FIRMADO']."', 
                    '".$i_data['envio_correo']."', 
                    '".$i_data['TIPOEMISION']."'
                    )";
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
                    $w_respuesta[] = array(
                        'SCOPE_IDENTITY'           =>  utf8_encode($row->SCOPE_IDENTITY)
                        
                    );
                }
                $o_respuesta=array('error'=>'0','mensaje'=>'ok','datos'=>$w_respuesta);
            }

            //$o_respuesta=array('error'=>'0','mensaje'=>'Venta creada exitosamente','datos'=>$i_data);   
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}

function registrarDetalleCompra($i_data){
    try{
        $Log=new IgtechLog ();
        $Log->Abrir();
        $Log->EscribirLog(' Registrar Venta ');
        $Log->EscribirLog(' DATOS DE ENTRADA');
        $Log->EscribirLog(' Datos: '.var_export($i_data,true));
        $ws_conexion=ws_coneccion_bdd();
        $insert_sql="insert into DETALLE_FACTURA_COMPRA (
            COD_FACTURA_COMPRA, 
            REF, 
            IDPRODCOM, 
            ID_IMPUESTO, 
            CANTIIDAD, 
            PRECIO, 
            SUBTOTAL, 
            IVA, 
            TOTAL, 
            BASE_NO_OBJETO_IVA, 
            BASE_IMPONIBLE_CERO, 
            BASE_IMPONIBLE_DOCE, 
            PORCENTAJE_RETENCION_FUENTE, 
            PORCENTAJE_IMPUESTO_RETENCION_IVA, 
            RETENCION_IMPUESTO_RENTA, 
            RETENCION_IMPUESTO_IVA, 
            DESCUENTO, 
            ICE) values(
            ".$i_data['COD_FACTURA_COMPRA'].", 
            '".$i_data['REF']."', 
            ".$i_data['IDPRODCOM'].", 
            ".$i_data['ID_IMPUESTO'].", 
            ".$i_data['CANTIIDAD'].", 
            ".$i_data['PRECIO'].", 
            ".$i_data['SUBTOTAL'].", 
            ".$i_data['IVA'].", 
            ".$i_data['TOTAL'].", 
            ".$i_data['BASE_NO_OBJETO_IVA'].", 
            ".$i_data['BASE_IMPONIBLE_CERO'].", 
            ".$i_data['BASE_IMPONIBLE_DOCE'].", 
            ".$i_data['PORCENTAJE_RETENCION_FUENTE'].", 
            ".$i_data['PORCENTAJE_IMPUESTO_RETENCION_IVA'].", 
            ".$i_data['RETENCION_IMPUESTO_RENTA'].", 
            ".$i_data['RETENCION_IMPUESTO_IVA'].", 
            ".$i_data['DESCUENTO'].", 
            ".$i_data['ICE'].")";

        $Log->EscribirLog(' Consulta: '.$insert_sql);    
        $result = sqlsrv_query($ws_conexion, $insert_sql);
        if ($result === false ){
            $o_respuesta=array('error'=>'9997','mensaje'=>sqlsrv_errors());
        }else{
            //$o_respuesta=array('error'=>'0','mensaje'=>'Venta creada exitosamente','datos'=>$i_data);   
        }
        sqlsrv_close($ws_conexion);        
    }catch(Throwable $e){
        $o_respuesta=array('error'=>'9999','mensaje'=>$e->getMessage());
    }
    $Log->EscribirLog(' Respuesta: '.var_export($o_respuesta,true));
    return $o_respuesta;
}


?>
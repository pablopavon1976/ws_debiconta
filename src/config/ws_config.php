<?php 
function ws_coneccion_bdd(){
	
	// $server = "host=localhost";
	// $puerto = "port=5432";
	// $user = "user=postgres";
	// $pass = "password=ivanjgp";
	// $bd = "dbname=debifact";
	// $ws_conexion = pg_connect("$server $puerto $bd $user $pass");
	// if(!$ws_conexion){
	// 	echo "Error: ".pg_last_error($ws_conexion);
	// }else{
	// 	return $ws_conexion;
	// }
	$serverName = "DESKTOP-KD3SIM9\SQL2016,1436"; //serverName\instanceName, portNumber (por defecto es 1433)
	$connectionInfo = array( "Database"=>"oviedo", 
							 "UID"=>"sa", 
							 "PWD"=>"@@1002485074@@");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	if( $conn===false ) {
		echo "Conexión no se pudo establecer.<br />";
	}else{
		return $conn;
	}
}

// function my_pg_query( $conexion=null, $sql ){
// 	 $rs = @pg_query( $conexion, $sql );
// 	if( $rs == false )
// 		 throw new Exception( "Error PostgreSQL ".pg_last_error($conexion) );
// 	return $rs;
// }

function array_to_xml($array, &$xml_user_info) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_user_info->addChild("$key");
                array_to_xml($value, $subnode);
            }else{
                $subnode = $xml_user_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }else {
            $xml_user_info->addChild("$key",htmlspecialchars("$value"));
        }
    }
}

?>
<?php
function enviarRespuesta($accept,$respuesta){
    switch ($accept){
		case 'text/plain':
			return	var_dump($respuesta);
		break;
		case 'application/json'	:
			return json_encode($respuesta,JSON_UNESCAPED_UNICODE);
		break;
		case 'text/xml':
			$xml = new SimpleXMLElement('<root/>');
			array_to_xml($respuesta,$xml);
			return $xml->asXml();
		break;	
		default:
            return json_encode($respuesta,JSON_UNESCAPED_UNICODE);
        break;
	}
}

?>
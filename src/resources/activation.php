<?php

require_once ('amfphp/services/babelia/Datasource.php');
require_once ('amfphp/services/babelia/Config.php');

	if ( !isset($_GET["hash"]) || !isset($_GET["user"]) )
		die("Te has equivocado");
	
	$settings = new Config();	
	$conn = new DataSource($settings->host, $settings->db_name, $settings->db_username, $settings->db_password);
	
	$sql = "SELECT id FROM users WHERE (name = '". $_GET["user"] ."' AND activation_hash = '". $_GET["hash"] ."') ";
	$result = $conn->_execute($sql);
	$row = $conn->_nextRow($result);
	
	if ( $row )
	{
		$sql = "UPDATE users SET active = 1, activation_hash = '' WHERE (name = '". $_GET["user"] ."' AND activation_hash = '". $_GET["hash"] ."');";
		$result = $conn->_execute($sql);
		
		if ( !$result )
			die("Error al ejecutar la activación");
		else
			die("La cuenta <b>".$_GET["user"]."</b> ha sido activada");
	}
	else
		die("Error al activar");

?>
<?php
set_include_path("..");
require_once("lib/common_service.php");
session_start();
if(isset($_SESSION['ident'])){
	unset($_SESSION['ident']);
	session_destroy();
	produceResult("ok");
	}
else{
	produceError("non authentifie");
	}
?>

<?php
set_include_path("..");
require_once('lib/common_service.php');

session_start();

$args = new ArgSetAuthent();
if(isset($_SESSION['ident'])){
	//produceError("deja authentifie");
	return;
	}

if(!$args->isValid()){
	produceError("arguments incorrects");
	return;
	}

try{
	$data = new DataLayer();
	$person = $data->authentifier($args->login, $args->password);
	if($person){
		$_SESSION['ident'] = $person;
		produceResult($person);
		}
	else{
		produceError("login incorrect");
		}
	}
	catch(PDOException $e){
		produceError($e->getMessage());
		}

?>

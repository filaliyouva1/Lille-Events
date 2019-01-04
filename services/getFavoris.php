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
	$favoris = $data->getFavoris($args->login);
  if($favoris){
    produceResult($favoris);
  }
  else{
    produceError('Oups');
  }
	}
	catch(PDOException $e){
		produceError($e->getMessage());
	}

?>

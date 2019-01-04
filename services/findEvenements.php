<?php
set_include_path('..');
require_once('lib/common_service.php');

$args = new ArgSetEvent();

if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
    $data = new DataLayer();

    $exclure=$_GET["exclure"];
    if(isset($exclure)){
    if($args->tri=="popularite"){
      $Events = $data->getEvents($args->categorie,$args->motcle,$exclure,"nbparticipants",$_GET['login']);
    }
    else if($args->tri=="dateevt"){
      $Events = $data->getEvents($args->categorie,$args->motcle,$exclure,"dateheure",$_GET['login']);
    }
    else{
      $Events = $data->getEvents($args->categorie,$args->motcle,$exclure,$args->tri,$_GET['login']);
    }
  }
  else{
    if($args->tri=="popularite"){
      $Events = $data->getEvents($args->categorie,$args->motcle,"non","nbparticipants",$data->login);
    }
    else if($args->tri=="dateevt"){
      $Events = $data->getEvents($args->categorie,$args->motcle,"non","dateheure",$data->login);
    }
    else{
      $Events = $data->getEvents($args->categorie,$args->motcle,"non",$args->tri,$data->login);
    }
  }


  if ($Events){
      produceResult($Events);}
  else{
      produceError("Pas d'evenements");}
} catch (PDOException $e){
    produceError($e->getMessage());
}

?>

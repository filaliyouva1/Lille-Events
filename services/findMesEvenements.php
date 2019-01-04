<?php
set_include_path('..');
require_once('lib/common_service.php');


try{
  $login = $_GET['login'];
    $data = new DataLayer();
    $Events = $data->getEvents($login);

    if ($Events)
        produceResult($Events);
    else
        produceError("Pas d'evenements");
} catch (PDOException $e){
    produceError($e->getMessage());
}

?>

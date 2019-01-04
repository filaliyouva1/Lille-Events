<?php
set_include_path('..');
require_once('lib/common_service.php');

try{
    $data = new DataLayer();
    $stats = $data->getStats();
    produceResult($stats);
}
catch (PDOException $e){
    produceError($e->getMessage());
}



?>

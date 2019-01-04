<?php
set_include_path('..');
require_once('lib/common_service.php');

$args = new ArgumentSetEtape();
if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
  $data = new DataLayer();
  $arrivee = $data->getArrivee($args->etape);
  produceResult($arrivee);    
}
catch (PDOException $e){
  produceError($e->getMessage());
}

?>

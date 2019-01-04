<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new ArgumentSetEquipe();
if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
  $data = new DataLayer();
  $coureurs = $data->getCoureurs($args->equipe);
  produceResult($coureurs);
}
catch (PDOException $e){
    produceError($e->getMessage());
}


?>

<?php
set_include_path('..');
require_once('lib/common_service.php');

$args = new ArgumentSetEquipe();
if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
    $data = new DataLayer();
    $infoEquipe = $data->getInfoEquipe($args->equipe);
    if ($infoEquipe)
        produceResult($infoEquipe);
    else
        produceError("equipe {$equipe} not found");
} catch (PDOException $e){
    produceError($e->getMessage());
}

?>

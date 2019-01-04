<?php
set_include_path('..');
require_once('lib/common_service.php');

$args = new ArgSetUser();
if (! $args->isValid()){
  produceError('argument(s) invalide(s)');
  return;
}

try{
  $data = new DataLayer();
  $descFile = $data->getAvatar($args->login);
  if ($descFile){
    $flux = is_null($descFile['data']) ? fopen('images/avatar_def.png','r') : $descFile['data'];
    $mimeType = is_null($descFile['data']) ? 'image/png' : $descFile['mimetype'];
    header("Content-type: $mimeType");
    fpassthru($flux);
    exit();
  }
  else
    produceError('fichier non reçu');
}
catch (PDOException $e){
  produceError($e->getMessage());
}

?>

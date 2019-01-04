<?php
 require_once('lib/common_service.php');
 session_start();

 if (isset($_SESSION['ident']))
  return;

 produceError('non authentifié');
 exit();
?>

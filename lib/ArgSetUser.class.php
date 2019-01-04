<?php
class ArgSetUser extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineNonEmptyString('login');
  }
}
?>

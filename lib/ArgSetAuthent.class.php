<?php
class ArgSetAuthent extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineNonEmptyString('login');
    $this->defineNonEmptyString('password');
  }
}
?>

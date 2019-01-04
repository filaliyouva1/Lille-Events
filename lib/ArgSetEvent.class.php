<?php
class ArgSetEvent extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineString('categorie',['default'=>'']);
    $this->defineString('motcle',['default'=>'']);
    $this->defineEnum('tri',['dateevt','datecreation','popularite'],['default'=>'dateevt']);

  }
}
?>

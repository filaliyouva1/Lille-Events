<?php
class ArgSetGetFav extends AbstractArgumentSet{
  protected function definitions(){
    $this->defineEnum('opposite',['true','false'],['default'=>'false','case'=>'to_lower']);
  }
}
?>

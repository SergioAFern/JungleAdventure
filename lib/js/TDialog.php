<?php
 include_once "TWindow.php";
?>

function TDialog(size) { 
  TWindow.call(this,size);
};

extend(TDialog,TWindow); //  inherit 

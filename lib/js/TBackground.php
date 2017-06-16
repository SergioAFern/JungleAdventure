<?php
 include_once "TView.php";
?>

function TBackground(pattern) {  
  TView.call(this); 
  this.pattern = pattern;
};

extend(TBackground,TView); //  inherit 

TBackground.prototype.draw = function() { // draw complete view
  return "<div id='view"+this.id+"' style='"+this.style()+"'></div>";
};

TBackground.prototype.style = function() { 
  return 'position:absolute; left:0px; top:0px; width:100%; height:100%; background-image: url(\"'+this.pattern+'\")';
};

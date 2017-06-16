<?php
 include_once "TView.php";
?>

function TInputLine(origin,size,maxLength) {  
  TView.call(this);
  this.origin = origin;
  this.size = size;
  this.maxLength = maxLength;
};

extend(TInputLine,TView); //  inherit TApplet

TInputLine.prototype.draw = function() { // draw complete view
  return TView.prototype.draw.call(this,'<input type="text" name="input'+this.id+'" maxlength="'+this.maxLength+'" size="'+this.maxLength+'" id="input'+this.id+'">');
};

TInputLine.prototype.setData = function(data) { 
 document.getElementById("input"+this.id).value=data;
};

TInputLine.prototype.getData = function() { 
 return document.getElementById("input"+this.id).value;
};

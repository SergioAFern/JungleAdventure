<?php
 include_once "TGroup.php";
?>

function TMenuBar(origin,size,items) {  
   TGroup.call(this,items);
   this.origin=origin;
   this.size=size;

  // internal
  this.background ='';
  this.cursorImage ='';
};

   extend(TMenuBar,TGroup); //  inherit 

TMenuBar.prototype.draw = function() { 
  return TView.prototype.draw.call(this,TGroup.prototype.draw.call(this));
};

TMenuBar.prototype.style = function() {
  return TView.prototype.style.call(this)+"overflow:hidden; top:"+this.origin.y+"px; left:"+this.origin.x+"px; width:"+this.size.x+"px; height:"+this.size.y+"px;"
};


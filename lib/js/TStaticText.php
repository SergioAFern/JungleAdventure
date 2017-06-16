<?php
 include_once "TView.php";
?>

function TStaticText(position,size,text) {  
  TView.call(this);
  this.position = position;
  this.text = text;
  this.size = size;
};

extend(TStaticText,TView); //  inherit TApplet

TStaticText.prototype.draw = function() { // draw complete view
  return '<div id="view'+this.id+'" style="'+this.style()+'">'+this.text+'</div>';
};

TStaticText.prototype.style = function() {
  return style='font-family:'+document.body.style.fontFamily+'; color:white; font-size:18pt; text-shadow: 4px 4px black; position:absolute; top:'+this.position.y+'px; left:'+this.position.x+'px; width:'+this.size.x+'px; height:'+this.size.y+'px;';
};
 

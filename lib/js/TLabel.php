<?php
 include_once "TView.php";
?>

function TLabel(position,text,command,fontSize) {  
  TView.call(this);
  this.position = position;
  this.text = text;
  this.command = command.replace(/"/g,"\'");
  this.fontSize = 24;
  if (arguments.length === 4) {
     this.fontSize = fontSize;
  }
};

extend(TLabel,TView); //  inherit TApplet

TLabel.prototype.draw = function() { // draw complete view
  return '<div id="view'+this.id+'" style="'+this.style()+';white-space:nowrap;" onmouseover="this.style.color=\'#ADD8E6\'" onmouseout="this.style.color=\'white\'" onclick="'+this.command+'">'+this.text+'</div>';
};

TLabel.prototype.style = function() {
  return style='font-family:'+document.body.style.fontFamily+'; color:white; font-size:'+this.fontSize+'px; text-shadow: 4px 4px black; position:absolute; top:'+this.position.y+'px; left:'+this.position.x+'px;';
};
 

<?php
 include_once "TView.php";
?>

var SBmouseover ="this.style.WebkitFilter=\'invert(25%)\';";
var SBmouseout ="this.style.WebkitFilter=\'invert(0%)\';";

if (/firefox/i.test(navigator.userAgent)) {
  SBmouseover ="this.style.filter=\'invert(25%)\';";
  SBmouseout ="this.style.filter=\'invert(0%)\';";
} else if ((/trident/i.test(navigator.userAgent)) ||  (/msie/i.test(navigator.userAgent))){
  SBmouseover ="this.style.opacity='0.5';";
  SBmouseout ="this.style.opacity='1.0';";
};

function TSpeedButton(position,image,command,style,other) {
  TView.call(this);
  this.position = position;
  this.image = image;
  this.command = command.replace(/"/g,"\'");
  this._style='';
  this.other='';
  if (arguments.length >= 4) {
     this._style=style;
  };
  if (arguments.length === 5) {
     this.other=other;
  };
};

extend(TSpeedButton,TView); //  inherit TApplet

TSpeedButton.prototype.draw = function() { // draw complete view
  var html ='<img style="'+this.style()+'" id="view'+this.id+'" src="'+this.image+'" '+this.other;
  if (this.command!='') html+=' onmouseover="'+SBmouseover+'" onmouseout="'+SBmouseout+'" onclick="'+this.command+'"';
  html+='>';
  return html;
};

TSpeedButton.prototype.style = function() {
  return 'position:absolute; top:'+this.position.y+'px; left:'+this.position.x+'px;'+this._style;
};


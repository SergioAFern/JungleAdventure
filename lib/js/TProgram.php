<?php
 include_once "TGroup.php";
?>

var MenuBar;
var Application;

TWrapper = function(content) {
  TView.call(this);
  this.content = content;
};
   extend(TWrapper,TView); //  inherit TApplet

TWrapper.prototype.draw = function() {
  return TView.prototype.draw.call(this);
};


function TProgram() { 
  Application = this;
  TGroup.call(this,[]);
};

extend(TProgram,TGroup); //  inherit 

TProgram.prototype.InitDeskTop = function() {
  return new TWrapper('');
};

TProgram.prototype.InitScreen = function() {// insert any additonal code you want executed when windows resizes or redrawn
};

TProgram.prototype.InitMenuBar = function() {
  return new TWrapper('');
};

TProgram.prototype.InitStatusBar = function() {
  return new TWrapper('');
};


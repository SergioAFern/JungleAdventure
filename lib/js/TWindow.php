<?php
 include_once "TGroup.php";
 include_once "TFrame.php";
 include_once "TScrollBar.php";
?>

var wfClose = 4;

function TWindow(size) {  
  TGroup.call(this,[new TFrame(size)]);
  this.size=size;
  this.flags = wfClose;
  this.title='';
};

extend(TWindow,TGroup); //  inherit 

TWindow.prototype.draw = function() { // draw complete view
  return TView.prototype.draw.call(this,TGroup.prototype.draw.call(this));
};


TWindow.prototype.close = function() { // draw complete view
  this.hide();
};

TWindow.prototype.InitFrame = function() { // draw complete view
  return'';
};

TWindow.prototype.StandardScrollBar = function(AOptions) { // draw complete view
  var sb = new TScrollBar(this,AOptions);
  this.list.push(sb);
  return sb;
};

TWindow.prototype.setTitle = function(title) { // draw complete view
  this.title='&nbsp;'+title+'&nbsp;&nbsp;';
  document.getElementById("title"+this.id).innerHTML='&nbsp;'+title+'&nbsp;&nbsp;';
};


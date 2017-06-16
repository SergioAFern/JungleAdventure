<?php
 include_once "TVector3.php";
 include_once "TApplet.php";
 include_once "TEvent.php";
 include_once "extend.php";
?>
var sfVisible   = 0x001;
var sfCursorVis = 0x002;
var sfCursorIns = 0x004;
var sfShadow    = 0x008;
var sfActive    = 0x010;
var sfSelected  = 0x020;
var sfFocused   = 0x040;
var sfDragging  = 0x080;
var sfDisabled  = 0x100;
var sfModal     = 0x200;
var sfExposed   = 0x800;

function TView() { 
   TApplet.call(this);
   this.owner = null;
   this.origin = new TVector3();
   this.size = new TVector3();
   this.cursor = new TVector3();
   this.growMode = 0;
   this.dragMode = 0;
   this.helpCtx = null;
   this.options = 0;
   this.eventMask = 0;
   this.content='';
   this._style='';
};


extend(TView,TApplet); //  inherit TApplet

TView.prototype.ClearEvent = function(ev) {
  ev.what=evNothing;
  ev.event.preventDefault();
};

TView.prototype.draw = function(content) { // draw complete view
  if (arguments.length === 0) {
   content="";
  };
  return "<div id='view"+this.id+"' style='"+this.style()+"'>"+this.content+content+"</div>";
};

TView.prototype.GetHelpCtx = function() {
  return null;
};

TView.prototype.HandleEvent = function(ev) { 
 // this.ClearEvent(ev);
};

TView.prototype.hide = function() { // draw complete view
 // document.getElementById("view"+this.id).style.visibility="hidden";
 // using display none makes rendering faster
 document.getElementById("view"+this.id).style.display="none";
};

TView.prototype.moveTo = function(x,y) {
  this.origin.init(x,y,0);
  document.getElementById("view"+this.id).style.left=x+'px';
  document.getElementById("view"+this.id).style.top=y+'px';
};

TView.prototype.show = function() { // draw complete view
 document.getElementById("view"+this.id).style.display="block";
};

TView.prototype.style = function() {
  return "position:absolute; left:"+this.origin.x+"px; top:"+this.origin.y+"px; "+this._style;
};


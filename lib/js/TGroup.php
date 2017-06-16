<?php
 include_once "TView.php";
?>

function TGroup(list) {
  TView.call(this);
  this.list=list; 
  this.current=list[0];
  var self=this;
  this.list.forEach(function(item) { item.owner=self;});
};

extend(TGroup,TView); //  inherit

TGroup.prototype.draw = function() { // draw complete view
   var html = '';
   this.list.forEach(function(item) {
    html+=item.draw();
   });
  return html;
 };

TGroup.prototype.HandleEvent = function(ev) { 
   this.list.forEach(function(item) {
      item.HandleEvent(ev);
   });
};


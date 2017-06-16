<?php
 include_once "TView.php";
?>

function TImage(origin,image,command,size) { 
  TView.call(this);
  this.origin = origin;
  this.image = image;
  this.command='';
  if (arguments.length === 3) {
     this.command = command.replace(/"/g,"\'");
  };
  if (arguments.length === 4) {
     this.size = size;
  } else this.size=null;
};

extend(TImage,TView); //  inherit TApplet

TImage.prototype.draw = function() { // draw complete view
  var size='';
  if (this.size!=null) size='width="'+this.size.x+'" height="'+this.size.y+'"';
  var html = '<img style="'+this.style()+'" id="view'+this.id+'" src="'+this.image+'" '+size+'>';
  if (this.command!='') html='<a id="link'+this.id+'" href="'+((typeof this.command !== 'undefined')?this.command:'#')+'">'+html+'</a>';
  return html;
};

TImage.prototype.style = function() { 
  return 'position:absolute; top:'+this.origin.y+'px; left:'+this.origin.x+'px;';
};


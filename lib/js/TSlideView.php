<?php
 include_once "TView.php";
 include_once "TSpeedButton.php";
?>
var SlideActive = true; // if slide is not active, then you cant slide (scrolll, left or right), 
                        // if slide is set to false, it auto moves to beginning 

function TSlideView(origin,size,data) {
   TView.call(this); 
   this.origin=origin;
   this.size = size;
   this.index=1;
   this.list = data; 

   // internal used data
   this.left =  new TSpeedButton({x:0,y:(origin.y+size.y/2-28)},'/image/left-active.png','AppletList['+this.id+'].prev();');
   this.right = new TSpeedButton({x:(origin.x+size.x-56),y:(origin.y+size.y/2-28)},'/image/right-active.png','AppletList['+this.id+'].next();');
   this.offset=0;
   this.html;
   this.halt = false;
   this.sliding = false;
};

   extend(TSlideView,TView); //  inherit 

TSlideView.prototype.draw = function() { // draw complete view
   this.html="<div id='content"+this.id+"' style='position:relative; left:0px;'><table cellspacing='0' cellpadding='0' border='0'><tr>";

   for(var i=0;i<this.list.length;i++) {
      this.html+="<td>"+this.list[i].content.draw()+"</td>";
   };
   this.html+="</tr></table></div>";

  return TView.prototype.draw.call(this,this.html)+this.left.draw()+this.right.draw();
};

TSlideView.prototype.style = function() {
  return TView.prototype.style.call(this)+"overflow:hidden; top:"+this.origin.y+"px; left:"+this.origin.x+"px; width:"+this.size.x+"px; height:"+this.size.y+"px;"
};

TSlideView.prototype.slide = function() { 
 if (this.sliding && this.halt) { // dont need a this.sldiing variable, just check that this.state, is not set to stINactive
   this.halt=false;
   this.sliding=false;
   document.getElementById("content"+this.id).style.left="0px";
   this.offset=0;
   this.index=1;
   this.state = stActive;
   return true;
 };
 this.halt=false;
 this.sliding=true;
 if (this.offset!=this.list[this.index-1].content.origin.x) {
   var i=this.id;
   var delta=(this.offset>this.list[this.index-1].content.origin.x)?-20:20;
   this.offset+=delta; // NOTE: lets use a timer to calculate the new offset more accurately
   // when you finish sliding, run the this.list[this.index-1].execute code
   if (this.offset==this.list[this.index-1].content.origin.x) { 
      eval(this.list[this.index-1].execute);
      document.getElementById("content"+this.id).style.left="-"+this.offset+"px";
      this.state = stActive;
   }
   setTimeout(function(){AppletList[i].slide();},1000/25);
 } else this.sliding=false;

};


TSlideView.prototype.update = function() { 

 if (this.state==stActive) {
    if (this.index>1) {this.left.show();} else {this.left.hide();};
    if (this.list.length>this.index) {this.right.show();} else {this.right.hide();};
 }

 if (this.offset!=this.list[this.index-1].content.origin.x) {
   var i=this.id;
   document.getElementById("content"+this.id).style.left="-"+this.offset+"px";
   setTimeout(function(){AppletList[i].update();},0);
 }

};

TSlideView.prototype.HandleEvent = function(ev) { 
   switch(ev.what) {
     case evKeyDown:
        switch(ev.event.keyCode) {
          case 37: this.prev();
                   this.ClearEvent(ev);  
                   return false; break; // left arrow pressed
          case 39: this.next();
                   this.ClearEvent(ev); 
                   return false; break; // right arrow pressed
        }
        break;
   };
  return 0;
};


TSlideView.prototype.prev = function() {
 if ((this.index>1) && (this.state==stActive)) { 
    this.state=stInActive;
    this.index--;
    this.update();
    this.slide();
 }
};

TSlideView.prototype.next = function() {
 if ((this.list.length>this.index) && (this.state==stActive)) {
    this.state=stInActive;
    this.index++;
    this.update();
    this.slide();
 }
};

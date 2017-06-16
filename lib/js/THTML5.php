<?php
 include_once "utils.php";
 include_once "TProgram.php";
 include_once "TMenuBar.php";
?>

// THTML5 v 0.1.1 by Sergio Fernandez august 6, 2015
// THTML5 v 0.1.2 by Sergio Fernandez updated April 14, 2016
// this is the base class inhereted by all html5 Apps.
// it will take care of basic routines, like making teh game full screen.
// and dispalying a rotate device message on handheld devices when orientation is wrong
// also provides some basic game routines to  be used by all games.
function THTML5(width,height,depth,overflow_width,overflow_height) {   
   this.overflow = new TVector3(); // often instead of a boxed game, we want a full screen game, the ovrflow is an extra distance around the game, added to show on the browser in the game world, to make sure the entire browser screen is sued without blank space around the game showing.
   if (arguments.length === 5) { 
     this.overflow.init(overflow_width,overflow_height,0);
   } else { this.overflow.init(0,0,0);};
   TProgram.call(this); 
   this.size.init(width,height,depth);
   this.list=[this.InitDeskTop(),this.InitMenuBar(),this.InitStatusBar(),new TWrapper(this.postContent())]; 

   this.actual = new TVector3();  // size and actual are used to control screen orientation, and scaling.
 
   this.aspect = this.size.x/this.size.y;
   this.scaler = 1;

   this.preLoadList = this.preload();
   document.body.style=this.bodyStyle(); 
   document.body.innerHTML+=this.draw()
                          +"<img id='loading' style='position:fixed;left:'+(this.size.x/2)+'px; top:'+(this.size.y/2)+'px;' src='/image/loading.gif' width='"+(this.size.x/2)+"' height='"+(this.size.y/2)+"'>";

   var self = this;
   if (mobileDevice) {setTimeout(function(){self.InitScreen();},1000);} else self.InitScreen();
   // the setTimeout delay on the resize event, give a bit of extra time for the dom dimension data to properly update
   window.addEventListener("orientationchange",function(){ setTimeout(function(){self.InitScreen();},1000);},false);
   window.addEventListener("resize",function(){ 
     if (mobileDevice) {setTimeout(function(){self.InitScreen();},1000);} else self.InitScreen();
   },false);
   window.addEventListener("touchmove",function(ev){ ev.preventDefault();},false);

   var mEvent =(/Firefox/i.test(navigator.userAgent))?"DOMMouseScroll":"mousewheel";
   document.addEventListener(mEvent,function(ev){ var event = new TEvent(ev); event.what=evMouse; self.HandleEvent(event);},false);
   window.addEventListener("keydown",function(ev){ var event = new TEvent(ev); event.what=evKeyDown; self.HandleEvent(event);},false);

 // show content once evething loads
  this.pending = this.preLoadList.length;
  this.preLoadList.forEach( 
     function(item){
       var img = new Image();
       img.src=item;
       img.onload = function() { 
          Application.pending-=1;
          if (Application.pending==0) 
             document.getElementById('loading').style.visibility='hidden';
       };
   });
};

   extend(THTML5,TProgram); //  inherit TApplet

THTML5.prototype.preload = function() {
  // before starting game, make sure this resources have been preloaded.
  return ['/image/1x1.png'];
};

THTML5.prototype.draw = function() {
  return TView.prototype.draw.call(this,TProgram.prototype.draw.call(this));
};

THTML5.prototype.style = function() {
  return TGroup.prototype.style.call(this)+"top:0px; left:0px;";
};

THTML5.prototype.bodyStyle = function() {
  return "overflow:hidden; margin:0px; padding:0px; image-rendering:optimizedSpeed;"; 
};


THTML5.prototype.postContent = function() {
 return "<image id='rotate' src='/image/1x1.png' style='position:absolute; top:0px; left:"+this.overflow.x+"px' border='0'>";
};

THTML5.prototype.InitScreen = function() {  
  window.scrollTo(0,0); 

  this.actual.init(window.innerWidth,window.innerHeight,this.size.z);

  // scale io with no black spaces on top or sides.
  this.scaler=(this.actual.x/this.size.x)<(this.actual.y/this.size.y)?(this.actual.x/this.size.x):(this.actual.y/this.size.y);

  var real_aspect = this.actual.x/this.actual.y;
    this.internal = {x:((this.actual.x-this.scaler*(this.size.x+2*this.overflow.x))/2),y:0};
    setTransform(document.getElementById('view'+this.id).style,"translate("+((this.actual.x-this.scaler*(this.size.x+2*this.overflow.x))/2)+"px,0px) scale("+this.scaler+")");
    setTransformOrigin(document.getElementById('view'+this.id).style,"left top");
   
    window.scrollTo(0,0); 

  if (mobileDevice) { 
    document.getElementById("rotate").src="/image/1x1.png"; 
    document.getElementById("rotate").width=1;
    // if wrong orientation, show rotate device icon
    if ((this.aspect>1) && (real_aspect<1)) { 
       document.getElementById("rotate").src="/image/rotate_side.png";
       document.getElementById("rotate").width=this.size.x;
    } else if ((this.aspect<1) && (real_aspect>1)) {  
       document.getElementById("rotate").src="/image/rotate_up.png";
       document.getElementById("rotate").height=this.size.y;
    }
  }
};


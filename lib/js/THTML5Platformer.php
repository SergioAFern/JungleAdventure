<?php
 $engine = 1.0;
 $build = 0;

 include_once "TObjectViewManager.php";
 include_once "TGridViewManager.php";
// include_once "TDialog.php";
// include_once "TImage.php";
// include_once "TSpeedButton.php";
// include_once "TLabel.php";
// include_once "TMenuBar.php";
// include_once "THTML5.php";
?>

// *************** TExitDialog ***************
function TExitDialog() {
    TDialog.call(this,{x:490,y:200});
    this.list.push(new TWrapper('<br>&nbsp; Are you sure you want to quit?'),
                   new TSpeedButton({x:80,y:100},'image/box.png','AppletList['+this.id+'].quit();'),
                   new TLabel({x:130,y:120},'Yes','AppletList['+this.id+'].quit();'),
                   new TSpeedButton({x:280,y:100},'image/box.png','AppletList['+this.id+'].hide();'),
                   new TLabel({x:340,y:120},'No','AppletList['+this.id+'].hide();'));
};

  extend(TExitDialog,TDialog);


TExitDialog.prototype.style = function() { 
  return TGroup.prototype.style.call(this)+'top:220px; left:500px; width:'+this.size.x+'px; height:'+this.size.y+'px; font-family:ariel; color:white; font-size:24pt; text-shadow: 2px 2px black; display:none;';
};

TExitDialog.prototype.quit = function() { 
  Application.worker.terminate();
  document.location.href="/";
};

var ExitDialog = new TExitDialog();


// **********************************************

// *************** TAnimationDiv ***************
function TAnimationDiv(pos,size,_class) {
    TView.call(this);
    this.origin = pos;
    this.size=size;
    this._class=_class; 
};

  extend(TAnimationDiv,TView);


TAnimationDiv.prototype.draw = function() { 
  return '<div id="view'+this.id+'" class="'+this._class+'" style="position:absolute;top:'+this.origin.y+'px; left:'+this.origin.x+'px; width:'+this.size.x+'px; height:'+this.size.y+'px;"></div>';
};


// **********************************************

// *************** TMessageDialog ***************
function TMessageDialog() { 
    TDialog.call(this,{x:445,y:280});
    this.content = new TView();
    this.video = new TAnimationDiv({x:8,y:8},{x:384,y:256},'start');
    this.text = new TLabel({x:10,y:230},'','','24px; text-align: center; width:400');
    this.list.push(this.content,this.text);
    this.dialog; // text to display while playing video.
   // internal variables
   this.start=0; // start time
   this.end=0; // end time
};

  extend(TMessageDialog,TDialog);


TMessageDialog.prototype.style = function() { 
  return TGroup.prototype.style.call(this)+'top:140px; left:500px; width:'+this.size.x+'px; height:'+this.size.y+'px; font-family:ariel; color:white; font-size:24pt; text-shadow: 2px 2px black; display:none;';
};

TMessageDialog.prototype.show = function() {
  this.video._class=shared.dialog.class;
  this.dialog = shared.dialog.dialog;
  
  document.getElementById("view"+this.content.id).innerHTML=this.video.draw();
  TDialog.prototype.show.call(this);
  this.start=Date.now();
  this.end = this.start+(shared.dialog.duration); 
  this.current=this.start;
  shared.dialog=null;
  setTimeout(function() {MessageDialog.run();},10);
};

TMessageDialog.prototype.run = function() { 
  this.current = Date.now(); 
  if (this.dialog.length>0) {
    if ((this.current-this.start)>this.dialog[0].start) 
      document.getElementById("view"+this.text.id).innerHTML=language.get(this.dialog[0].text);
    if ((this.current-this.start)>this.dialog[0].end) {
      this.dialog.shift();
      document.getElementById("view"+this.text.id).innerHTML='';
    };
  };  
  if (this.current>this.end) { this.hide();
  }
  else { setTimeout(function() {MessageDialog.run();},10);
 }
};

var MessageDialog = new TMessageDialog();


// **********************************************

function THTML5Platformer(width,height,depth,overflow_width,overflow_height) {  
   this.clickregion = new TSpeedButton({x:0,y:0},'/image/1x1.png','','width:'+(width+2*overflow_width)+'px;height:'+(height+2*overflow_height)+'px;',' onmousedown="Application.getMouse(event)" ontouchstart="Application.getMouse(event)" ');
   THTML5.call(this,width,height,depth,overflow_width,overflow_height); 
   // all platforms have a player, the character you control.
   // this player also privides detail used to capture mouse events
   // that is, I will determing if the user wants to move forward, back, jump, duck, based on were on the
   // screen the user preses relative to the region the player uses up
   this.plane;
   this.player;
   this.objectManager = new TObjectViewManager(); // Display all movable objects on the visible screen
   this.gridManager;
   //  internal variables
   this.locked = false; 
if (window.Worker) { // have all the logic and calulcation on the worker, and the GUI in the main code.
  this.worker = new Worker("worker.php");
  this.worker.addEventListener('message',function (e) {
     if (Application.locked) return 0;
     Application.locked=true;
     Application.HandleMessage(e);
     Application.locked=false;
  },false);
} else {
 // display error dialog saying you need webworker to play this game.
}

};


  extend(THTML5Platformer,THTML5);

THTML5Platformer.prototype.InitScreen = function() { 
  THTML5.prototype.InitScreen.call(this); 
  // force recalculation of MouseAdjust, after ever screen resize
  this.MouseAdjust = null;
};

THTML5Platformer.prototype.upback = function() {this.worker.postMessage("upback");};
THTML5Platformer.prototype.up = function() {this.worker.postMessage("up");};
THTML5Platformer.prototype.upforward = function() {this.worker.postMessage("upforward");};
THTML5Platformer.prototype.back = function() {this.worker.postMessage("backward");};
THTML5Platformer.prototype.stop = function() {this.worker.postMessage("stop");};
THTML5Platformer.prototype.forward = function() {this.worker.postMessage("forward");};
THTML5Platformer.prototype.downback = function() {this.worker.postMessage("downback");};
THTML5Platformer.prototype.down = function() {this.worker.postMessage("down");};
THTML5Platformer.prototype.downforward = function() {this.worker.postMessage("downforward");};

THTML5Platformer.prototype.HandleEvent = function(ev) {
   switch(ev.what) {
     case evKeyDown:
        switch(ev.event.keyCode) {
          case 12: this.stop();
                   this.ClearEvent(ev);  
                   return false; break; // stop key (keypad 5) pressed
          case 33: this.upforward();
                   this.ClearEvent(ev);  
                   return false; break; // left arrow pressed
          case 34: this.downforward();
                   this.ClearEvent(ev);  
                   return false; break; // left arrow pressed
          case 35: this.downback();
                   this.ClearEvent(ev);  
                   return false; break; // left arrow pressed
          case 36: this.upback();
                   this.ClearEvent(ev);  
                   return false; break; // left arrow pressed
          case 37: this.back();
                   this.ClearEvent(ev);  
                   return false; break; // left arrow pressed
          case 38: this.up();
                   this.ClearEvent(ev);  
                   return false; break; // up arrow pressed
          case 39: this.forward();
                   this.ClearEvent(ev); 
                   return false; break; // right arrow pressed
          case 40: this.down();
                   this.ClearEvent(ev); 
                   return false; break; // down arrow pressed
          case 120: debug=true; // NOTE: remove this line, its here only for debuging purpose
                   this.ClearEvent(ev); 
                   return false; break; // down arrow pressed
        }
        break;
     case evMouse: 
        var position = this.getPosition(ev.event);
        if (position.x<this.player.origin.x) { 
          if (position.y<this.player.origin.y) {this.upback();}
          else if (position.y>(this.player.origin.y+this.player.size.y)) {this.downback();}
          else this.back();
        } else if (position.x>(this.player.origin.x+this.player.size.x)) {
          if (position.y<this.player.origin.y) {this.upforward();}
          else if (position.y>(this.player.origin.y+this.player.size.y)) {this.downforward();}
          else this.forward();
        } else {
          if (position.y<this.player.origin.y) {this.up();}
          else if (position.y>(this.player.origin.y+this.player.size.y)) {this.down();}
          else this.stop();
        };

        this.ClearEvent(ev);        
     break;
   };
  return 0;
};

THTML5Platformer.prototype.getMouse = function(ev) {
  var event = new TEvent(ev); 
  event.what=evMouse; 
  this.HandleEvent(event);
};

if (is_touch_device()) {

THTML5Platformer.prototype.getPosition = function (ev) {
  var el = ev.currentTarget;
  var xPos = ev.touches[0].clientX;
  var yPos = ev.touches[0].clientY;
  // NOTE: the bottom part of the code is the same for both touch and none touch device, optomized this later ok
 
if (this.MouseAdjust==null) {
  this.MouseAdjust = {x:this.internal.x,y:this.internal.y};
  while (el) {
    if (el.tagName == "BODY") {
      // deal with browser quirks with body/window/document and page scroll
      var xScroll = el.scrollLeft || document.documentElement.scrollLeft;
      var yScroll = el.scrollTop || document.documentElement.scrollTop;
 
      this.MouseAdjust.x += (el.offsetLeft - xScroll + el.clientLeft);
      this.MouseAdjust.y += (el.offsetTop - yScroll + el.clientTop);
    } else { 
      // for all other non-BODY elements
      this.MouseAdjust.x += (el.offsetLeft - el.scrollLeft + el.clientLeft);
      this.MouseAdjust.y += (el.offsetTop - el.scrollTop + el.clientTop);
    }
 
    el = el.offsetParent;
  };
};
  xPos = (xPos-this.MouseAdjust.x)/this.scaler;
  yPos = (yPos-this.MouseAdjust.y)/this.scaler;
  return {
    x: xPos,
    y: yPos
  };
};

} else {

THTML5Platformer.prototype.getPosition = function (ev) {
  var el = ev.currentTarget;
  var xPos = ev.clientX;
  var yPos = ev.clientY;

if (this.MouseAdjust==null) {
  this.MouseAdjust = {x:this.internal.x,y:this.internal.y};
  while (el) {
    if (el.tagName == "BODY") {
      // deal with browser quirks with body/window/document and page scroll
      var xScroll = el.scrollLeft || document.documentElement.scrollLeft;
      var yScroll = el.scrollTop || document.documentElement.scrollTop;
 
      this.MouseAdjust.x += (el.offsetLeft - xScroll + el.clientLeft);
      this.MouseAdjust.y += (el.offsetTop - yScroll + el.clientTop);
    } else { 
      // for all other non-BODY elements
      this.MouseAdjust.x += (el.offsetLeft - el.scrollLeft + el.clientLeft);
      this.MouseAdjust.y += (el.offsetTop - el.scrollTop + el.clientTop);
    }
 
    el = el.offsetParent;
  };
};
  xPos = (xPos-this.MouseAdjust.x)/this.scaler;
  yPos = (yPos-this.MouseAdjust.y)/this.scaler;
  return {
    x: xPos,
    y: yPos
  };
};

};

THTML5Platformer.prototype.HandleMessage = function(e) {
 
};

// over write this, to define the player region specific to your game.
THTML5Platformer.prototype.initPlayer = function() {
  return new TTileView({x:676,y:220,z:0},{x:125,y:200,z:0},{});
};

THTML5Platformer.prototype.initGrid = function() {
  return new TGridViewManager(this.size,new TVector3(256,256,0),{});
};

THTML5Platformer.prototype.initMoveable = function() {
 // NOTE: create a div for all moable objects, and set it to dispay=none
  return new TWrapper('');
};


THTML5Platformer.prototype.InitDeskTop = function() {
  this.gridManager = this.initGrid();
  this.player = this.initPlayer();
  return new TMenuBar({x:0,y:0},{x:(this.size.x+2*this.overflow.x),y:(this.size.y+2*this.overflow.y)},[this.gridManager,this.player,this.initMoveable(),this.clickregion,MessageDialog,ExitDialog]);
};


THTML5Platformer.prototype.InitMenuBar = function() {
  return new TMenuBar({x:this.overflow.x,y:0},{x:80,y:80},[new TSpeedButton({x:0,y:10},'/image/home.png','ExitDialog.show();')]);
};

THTML5Platformer.prototype.load = function(level) {
  // NOTE: Implement load level
 data = JSON.parse(include('level/'+level+'.lvl?i='+Date.now()));
 _plane = (""+data.planes).split(","); 
 document.getElementById("view"+this.id).style.backgroundImage="url(background/"+data.background+")";
 // NOTE: load planes 
 for(i=0;i<data.total_planes;i++) {
   // NOTE: put code to update each plane here.
  if (i!=(data.active_plane-1)) {
     document.getElementById("view"+this.plane[i].id).style.backgroundImage="url(image/"+_plane[i*5]+")";
  }
 }
};

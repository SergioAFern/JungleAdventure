<?php
 include_once "../../js/TAgent.php";
?>
function sumTime() {
  if (time>60) {
   Score(5);
   time-=60;
   setTimeout(sumTime,1000/2);
  } else { location.href="highscore.php";}
};
function youWon() {
  shared.won=true;
  hero.stop(); 
  setTimeout(sumTime,1000/2);
};

function checkPoint() { 
  hero.state=0; 
  hero.checkpoint=Application.camera.current.origin.x-512; 
  hero.speed.x=0;
  hero.acceleration.x=0;
  shared.lives-=1;
};

function youDie() { 
  hero.speed.x=0;
  hero.acceleration.x=0;
  setTimeout(checkPoint,1000); 
};

<?php include "state/human.php" ?>

TPlayer = function() { 
    TAgent.call(this); 
    this.state = 0; 
    this.direction = 1; 
    this.frames = 30;
    this.checkpoint = null;
    this.carry = null; // the enemy which is carrying you.
   // this.position.init(shared.camera.position,220,0); 
    this.maxspeed = new TVector3(); this.maxspeed.init(6,12,0);
    this.speed = new TVector3();
    this.acceleration = new TVector3();
    // NOTE: remove hurts states
    this.oldstate = 0;
    this.button = 0; // which region on the screen was clicked. 0 = nothing pressed, 1..9 = region on screen pressed 
};

extend(TPlayer,TAgent);

TPlayer.prototype.region = function() { // 0 = in above ground, 1 = on ground, 2 = below ground, add anothe state: 3 = on cave floor
  if (this.position.y>437) { 
    this.position.y=437;
    this.speed.y=0;
  }
  return this.position.y==220 ? 1:(this.position.y<220 ? 0:(this.position.y==437? 3:2));
};


TPlayer.prototype.changeState = function(st) { 
this.oldstate=this.state;
this.state=st;

// TODO : re-arrange order of states , so that I dont have to use a switch statment.to set the class
// set class
if (this.state==this.oldstate) return 0;
this.frame=0;

// set state
};


TPlayer.prototype.getCol = function() { ;
  var offset = -(Application.camera.current.origin.x & 255);
  var ret = cNone;
  if (this.position.y==220)  {
    if (game.GridManager.grid[3][3]!=2) ret |= cBottom; 
  }
  if (this.position.y>(220+64)) {
   if ((game.GridManager.grid[4][2]==7) && (offset>-100)) ret |= cLeft; 
   if ((game.GridManager.grid[4][4]==7) && (offset<-200)) ret |= cRight; 

   if (game.GridManager.grid[3][2]!=2) ret |= cTopLeft;
   if (game.GridManager.grid[3][4]!=2) ret |= cTopRight;
  } else {
   if (game.GridManager.grid[3][2]==7) ret |= cLeft; 
   if (game.GridManager.grid[3][4]==7) ret |= cRight; 
  }
  if (this.position.y>=437) { 
    this.position.y=437;
    this.speed.y=0;
    ret |= cBottom;
    if (game.GridManager.grid[3][3]!=2) ret |= cTop; 
  }
 return ret;
};

TPlayer.prototype.update = function() { 

// get variable
var colNum = this.getCol(); // *** collision
// ** currentState (this.state)
var SpeedX = abs(this.speed.x)>(this.maxspeed.x/2) ? 2:(this.speed.x==0? 0:1); // ** SpeedX  range: 0,1,2
var SpeedY = this.speed.y<0 ?-1:(this.speed.y>0? 1:0); // ** speedY  range: -1,0,1

// send Debug info, if error
//shared.debug=colNum+' '+this.state+' '+SpeedX+' '+SpeedY+' '+(this.direction<0?0:1)+' '+this.button;
if (transitionState[colNum]===undefined) {
   shared.debug='ERROR: '+colNum+' '+this.state+' '+SpeedX+' '+SpeedY+' '+(this.direction<0?0:1)+' '+this.button;
   return;
} else if (transitionState[colNum][this.state]===undefined) {
   shared.debug='ERROR: '+colNum+' '+this.state+' '+SpeedX+' '+SpeedY+' '+(this.direction<0?0:1)+' '+this.button;
   return;
} else if (transitionState[colNum][this.state][SpeedX]===undefined) {
   shared.debug='ERROR: '+colNum+' '+this.state+' '+SpeedX+' '+SpeedY+' '+(this.direction<0?0:1)+' '+this.button;
   return;
} else if (transitionState[colNum][this.state][SpeedX][SpeedY]===undefined) {
   shared.debug='ERROR: '+colNum+' '+this.state+' '+SpeedX+' '+SpeedY+' '+(this.direction<0?0:1)+' '+this.button;
   return;
} else if (transitionState[colNum][this.state][SpeedX][SpeedY][this.direction<0?0:1]===undefined) {
   shared.debug='ERROR: '+colNum+' '+this.state+' '+SpeedX+' '+SpeedY+' '+(this.direction<0?0:1)+' '+this.button;
   return;
} else if (transitionState[colNum][this.state][SpeedX][SpeedY][this.direction<0?0:1][this.button]===undefined) {
   shared.debug='ERROR: '+colNum+' '+this.state+' '+SpeedX+' '+SpeedY+' '+(this.direction<0?0:1)+' '+this.button;
   return;
} else shared.debug='';


this.changeState(transitionState[colNum][this.state][SpeedX][SpeedY][this.direction<0?0:1][this.button]);
//shared.debug+="["+this.state+"]";
// ** direction (this.direction)
// ** button (this.button)
//
  this.position.x=Application.camera.current.origin.x;

  this.frame++;
  if (this.frame>=this.frames) this.frame=0;

  this.button=0; // clear button


 switch(this.state) {
  case 0: if (this.oldstate!=this.state) {this.speed.init(0,0,0);this.acceleration.init(0,0,0);}; break;
  case 1: case 2: if (this.oldstate!=this.state)  {  this.acceleration.x=this.direction*0.06; };
      if (this.acceleration.x==0) {  this.speed.x*=((abs(this.speed.x)>0.09) ? 0.96 : 0 ); }; break;
  case 3: if (this.oldstate!=this.state) { this.speed.init(0,0,0);this.acceleration.init(0,0,0);}; 
          break;
  case 4: if (this.oldstate!=this.state)  {  this.acceleration.x=this.direction*0.06; };
          this.changeState((abs(this.speed.x)>0) ? 4: 3); 
          if (this.acceleration.x==0) {  this.speed.x*=((abs(this.speed.x)>0.09) ? 0.96 : 0 ); }
          break;
  case 5: if (this.oldstate!=this.state) {this.speed.y=-12;} else {this.speed.y*=0.9;};
          if (this.speed.y>-0.1) this.speed.y=0;
          this.acceleration.x*=0.2;
          if (this.acceleration.x<0.01) this.acceleration.x=0;
          break;
  case 6: if (this.oldstate!=this.state) {this.speed.y=0.2;} else {this.speed.y*=1.11;};
          if (this.frame==28) { 
            if (this.position.y<221) { this.changeState(0);  this.speed.y=0;this.position.y=220;} 
          } 
          break;
  case 7: if (this.oldstate!=this.state) {this.speed.y=0;};
          break;
  case 9: if (this.frame==28) this.changeState(0); 
          break;
  case 11: if (this.oldstate!=this.state) {this.speed.y=-12; this.speed.x=0;} else {this.speed.y*=0.95;};
           if (this.speed.y>-0.1) this.speed.y=0;
          break;
  case 12: if (this.oldstate!=this.state) {this.speed.y=-2.5;  this.speed.x=this.direction*3.5;};
           if (this.frame>9) Application.camera.slideAdd({x:5*this.direction,y:0,z:0});
           if (this.frame==28) { this.changeState(0); this.speed.y=0;this.position.y=220;}
          break;
 }

  this.speed.add(this.acceleration);

  var speed = abs(this.speed.x);
  this.speed.x=this.direction*(((this.state==4) ? Math.min(speed,this.maxspeed.x/2) : Math.min(speed,this.maxspeed.x)));
  this.position.add(this.speed);

};


TPlayer.prototype.render = function() {
if (this.state!=shared.state) { 
  this.oldstate=-1;
  this.changeState(shared.state);
}

if (shared.y!=this.position.y) { // this will replace change.ranformation
  this.position.y=shared.y;
}
};

TPlayer.prototype.upback = function() {
if (hero.checkpoint!=null) return 0;
  this.button=1; 
  
  if (this.speed.x>0) this.speed.x=0;
  this.direction=-1;
};

TPlayer.prototype.up = function() {
if (hero.checkpoint!=null) return 0;
  this.button=2; 
};

TPlayer.prototype.upforward = function() {
if (hero.checkpoint!=null) return 0;
  this.button=3; 

  if (this.speed.x<0) {this.speed.x=0;}
  this.direction=1;
};

TPlayer.prototype.backward = function() {
if (hero.checkpoint!=null) return 0;
    this.button=4; 

  this.direction=-1;
  if (this.speed.x>0) {this.speed.x=0;}
};

TPlayer.prototype.stop = function() {
  if (hero.checkpoint!=null) return 0;
  this.button=5; 
};


TPlayer.prototype.forward = function() {
if (hero.checkpoint!=null) return 0;
    this.button=6; 
  this.direction=1;
  if (this.speed.x<0) {this.speed.x=0;}
};


TPlayer.prototype.downback = function() {
if (hero.checkpoint!=null) return 0;
  this.direction=-1;
  this.acceleration.x=-0.06; 
  this.button=7; 

  if (this.speed.x<0) {this.speed.x=0;}
};

TPlayer.prototype.down = function() {
if (hero.checkpoint!=null) return 0;
  this.acceleration.x=0; 
  this.button=8; 
};

TPlayer.prototype.downforward = function() {
if (hero.checkpoint!=null) return 0;
  this.direction=1;
  this.acceleration.x=0.06; 
  this.button=9; 

  if (this.speed.x<0) {this.speed.x=0;}
};

TPlayer.prototype.fall = function() {
  this.acceleration.x=0; 
  this.speed.y=1.5;
  this.changeState(6);
};


TPlayer.prototype.drown = function() {
  this.speed.x=this.acceleration.x=0.0; 
  this.changeState(8);

  //shared.lives-=1;
  youDie();
};

TPlayer.prototype.hurt = function() {
  this.speed.init(0,0,0);
  this.acceleration.init(0,0,0); 
  this.position.y=220;// check if he is under ground or on ground 220 = on ground
  this.changeState(9);

  Score(-10); 

};

var dielock = false;
function playerUpdate() { 
 if (hero.checkpoint!=null) { // slide over to checkpoint
     if (hero.checkpoint>Application.camera.current.origin.x) { 
       Application.camera.slideAdd({x:8,y:0,z:0}); 
     }
     else if (hero.checkpoint<Application.camera.current.origin.x) { 
       Application.camera.slideAdd({x:-8,y:0,z:0}); 
     }
     else {
       hero.checkpoint=null; 
       dielock=false;
       hero.changeState(0);  
       if (hero.position.y<220) {hero.position.y=220;}
     }
  } else {hero.update(); }
};

TPlayer.prototype.die = function() {
  if (dielock) {return 0;}
  dielock=true;
  this.changeState(9);
  this.speed.init(0,0,0);
  this.acceleration.init(0,0,0); 
  this.position.y=220;// check if he is under ground or on ground 220 = on ground

  youDie(); 
};




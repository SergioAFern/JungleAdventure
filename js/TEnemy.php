<?php
 include_once "lib/js/extend.php";
?>
TEnemy = function(x,speed) {
  TAgent.call(this);
  this.origin = new TVector3(x,220,0);
  this.position.init(x,220,0);
  this.offset = 0;
  this.direction = -1;
};

extend(TEnemy,TAgent);

//=============================================
TEagle = function(x,y) {
  TAgent.call(this);
  this.class = 'eagle';
  this.origin = new TVector3(x,y,10);
  this.size = new TVector3(256,256,10);
  this.speed = new TVector3(6,0,0);
  this.direction = -1; // you can get the direction, from the speed. - or + speed will indicated direction
  // internal variable
  this.delta = new TVector3();
};

extend(TEagle,TAgent);

TEagle.prototype.update = function() {
  this.origin.add(this.speed);
  this.delta.add(this.speed);
  // change direction when gone far leff or far right
  if (Math.abs(this.delta.x)>768) { 
    this.speed.x*=-1;
    this.direction*=-1;
  };
 if ((hero.state==5) && 
     (hero.position.y>this.origin.y) && (hero.position.y<(this.origin.y+this.size.y)) && 
     ((hero.position.x+32)>this.origin.x) && (hero.position.x<(this.origin.x+this.size.x))){ 
    hero.state=7; 
    hero.carry=this; 
    hero.speed.y=0; 
    hero.acceleration.y=0;
  };
if ((hero.carry==this) && (hero.state==7))  {
      if (this.direction<0) { 
         Application.camera.slide({x:this.origin.x+5,y:0,z:0});
      } else {
         Application.camera.slide({x:this.origin.x+105,y:0,z:0});
      };
      hero.position.y=this.origin.y+234;
      hero.direction=-this.direction;
   };
};

//=============================================
TLion = function(x,y) {
  TAgent.call(this);
  this.class = 'lion';
  this.origin = new TVector3(x,y,10);
  this.size = new TVector3(256,136,10);
  this.speed = new TVector3(6,0,0);
  this.direction = -1; // you can get the direction, from the speed. - or + speed will indicated direction
  // internal variable
  this.delta = new TVector3();
};

extend(TLion,TAgent);

TLion.prototype.update = function() {
  this.origin.add(this.speed);
  this.delta.add(this.speed);
  // change direction when gone far leff or far right
  if (Math.abs(this.delta.x)>768) { 
    this.speed.x*=-1;
    this.direction*=-1;
  };

  if ((hero.state!=9) &&
     ((hero.position.y+160)>this.origin.y) && (hero.position.y<(this.origin.y+this.size.y)) && 
     ((hero.position.x+24)>this.origin.x) && (hero.position.x<(this.origin.x+this.size.x))
     ) {
     hero.die(); 
    this.speed.x*=-1;
    this.direction*=-1;
    this.delta.x*=-1;
  };
};



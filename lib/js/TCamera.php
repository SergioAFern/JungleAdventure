<?php
 include_once "extend.php";
 include_once "TVector3.php";
?>

function TCameraView() {
  this.origin = new TVector3();
  this.angle = new TVector3();
};

TCameraView.prototype.assign = function(camera) {
   this.origin.assign(camera.origin);
   this.angle.assign(camera.angle);
};

TCameraView.prototype.add = function(camera) {
   this.origin.add(camera.origin);
   this.angle.add(camera.angle);
};

TCameraView.prototype.subtract = function(camera) {
   this.origin.subtract(camera.origin);
   this.angle.subtract(camera.angle);
};


function TCamera() {  
   this.current = new TCameraView();
   this.last = new TCameraView();

   // internal camera movtion variable
   this.transition = false;
   this.original = new TCameraView();
   this.target = new TCameraView();
   this.start = this.end = new Date();
};

TCamera.prototype.move = function(origin,angle) {
   this.slide(origin);
   this.rotate(angle);
};

TCamera.prototype.moveTo = function(origin,angle,delta) {
  this.transition = true;

  this.start = new Date();
  this.end = this.start+delta;

  this.original.assign(this.current);

  this.target.origin.assign(origin);
  this.target.angle.assign(angle);
};

TCamera.prototype.get = function() {
  if (this.transition) {
     var now = new Date();

     if (now>this.end) {
       this.transition = false;
       this.move(this.target);
     } else {
       var result = new TCameraView();
       result.assign(this.target);  // r = target
       result.subtract(this.original); // r = target-original
       result = result.scale((now-this.start)/(this.end-this.start)); // r= (target-original)*(Tt-Ts)/(Te-Ts)
       result.add(this.original); // (target-original)*(Tt-Ts)/(Te-Ts)+original
       this.move(result);
     };
  };
  return this.current;
};

TCamera.prototype.slide = function(origin) {
  this.last.origin.assign(this.current.origin);
  this.current.origin.assign(origin);
};

TCamera.prototype.slideAdd = function(origin) {
  this.last.origin.assign(this.current.origin);
  this.current.origin.add(origin);
};

TCamera.prototype.rotate = function(angle) {
  this.last.angle.assign(this.current.angle);
  this.current.angle.assign(angle);
};



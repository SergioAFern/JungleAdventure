<?php
 include_once "../lib/js/TVector3.php";
?>
_TCamera = function(x,y,z) {
   this.origin = new TVector3();
   this.old = new TVector3();

   this.oldposition = -1;
   this.position = 0;
// some pre-calculated variables
   this.start;
   this.offset;
};


_TCamera.prototype.move = function(x) {
  //this.oldposition=this.position;

  this.position+=x;
  this.offset=-(this.position & 255);
  this.start =(this.position>>8)-3;
};

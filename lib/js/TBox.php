<?php
 include_once "TVector3.php";
?>

function TBox(x1,y1,z1,x2,y2,z2) {
     this.p1 = new TVector3();
     this.p2 = new TVector3();
     if (arguments.length === 6) {
        this.init(x1,y1,z1,x2,y2,z2);
     };
};

    TBox.prototype.init = function(x1,y1,z1,x2,y2,z2) {
      this.p1.init(x1,y1,z1);
      this.p2.init(x2,y2,z2);
      return this;
    };

    TBox.prototype.resize = function(x,y,z) {
      this.p2.x = this.p1.x + x;
      this.p2.y = this.p1.y + y;
      this.p2.z = this.p1.z + z;
    };

    TBox.prototype.isEmpty = function() {
      return (this.p1.x > this.p2.x) || (this.p1.y > this.p2.y) || (this.p1.z > this.p2.z);
    };

    TBox.prototype.intercept = function(b) {
      var inter = new TBox();
      
      inter.p1.x =Math.max(this.p1.x,b.p1.x);
      inter.p1.y =Math.max(this.p1.y,b.p1.y);
      inter.p1.z =Math.max(this.p1.z,b.p1.z);

      inter.p2 = minVector(this.p2,b.p2);
 
      return inter;
    };

    TBox.prototype.bounding = function(b) {
        return (this.p1.x<b.p2.x) && (this.p2.x>b.p1.x) && (this.p1.y<b.p2.y) && (this.p2.y>b.p1.y);
    };

    TBox.prototype.contains = function(b) {  // TRUE if b is completly inside of box
        if (b instanceof TVector3) {
         return (this.p1.x<=b.x) && (this.p2.x>=b.x) && (this.p1.y<=b.y) && (this.p2.y>=b.y) && (this.p1.z<=b.z) && (this.p2.z>=b.z);
    
        } else {
           return (this.p1.x<=b.p1.x) && (this.p2.x>=b.p2.x) && (this.p1.y<=b.p1.y) && (this.p2.y>=b.p2.y) && (this.p1.z<=b.p1.z) && (this.p2.z>=b.p2.z);
        }
    };

    TBox.prototype.grow = function(delta) {
      this.p1.subtract(delta);
      this.p2.add(delta);
    };

    TBox.prototype.move = function(delta) {	
      this.p1.add(delta);
      this.p2.add(delta);
    };

    TBox.prototype.assign = function(b) {	
      this.init(b.p1.x,b.p1.y,b.p1.z,b.p2.x,b.p2.y,b.p2.z);
    };

    TBox.prototype.width = function() {	
      return this.p2.x-this.p1.x;
    };


function TVector3(x,y,z) {
    this.x = 0;
    this.y = 0;
    this.z = 0;

    if (arguments.length === 3) {
     this.init(x,y,z);
    }
};

    TVector3.prototype.init = function(_x,_y,_z) {
      this.x = _x;
      this.y = _y;
      this.z = _z;
      return this;
    };

    TVector3.prototype.assign = function(v) {
      this.x = v.x;
      this.y = v.y;
      this.z = v.z;
      return this;
    };


    TVector3.prototype.magnitude = function() {
      return Math.sqrt(this.x*this.x+this.y+this.y+this.z*this.z);
    };

    TVector3.prototype.normalize = function() {
     var m = this.magnitude();
     if (m<0.00001) {
       this.init(this.x/m,this.y/m,this.z/m);
     } else { this.init(0,0,0);};
    };

    TVector3.prototype.add = function(delta) {
      this.x = this.x + delta.x;
      this.y = this.y + delta.y;
      this.z = this.z + delta.z;
    };

    TVector3.prototype.subtract = function(delta) {
      this.x = this.x - delta.x;
      this.y = this.y - delta.y;
      this.z = this.z - delta.z;
    };

    TVector3.prototype.negative = function() {
      return (new TVector3()).init(-this.x,-this.y,-this.z);
    };

    TVector3.prototype.scale = function(factor) {
      return (new TVector3()).init(factor*this.x,factor*this.y,factor*this.z);
    };
function minVector(a,b) {
  var mv = new TVector3();
  mv.init(Math.min(a.x,b.x),Math.min(a.y,b.y),Math.min(a.z,b.z));
  return mv;
};

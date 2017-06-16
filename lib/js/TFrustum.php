
function TFrustum() {
             this.deltaNear = new TVector3();
             this.deltaFar = new TVector3(); // near and far plane of frustum
             this.origin = new TVector3();
     }


     TFrustum.prototype.init = function(_origin,zNear,zFar,ScreenX,ScreenY) {
  // this is a semi- frustum, instead of having 6 planes, it only has two the near and far plane.
  // which run along the z axis. parallele to x and y plane.
    this.origin =_origin;  // move origin

    this.deltaNear.init(zNear,zNear*(ScreenX/2),zNear*(ScreenY/2));
    this.deltaFar.init(zFar,zFar*(ScreenX/2),zFar*(ScreenY/2));
};

     TFrustum.prototype.contains = function(p) {
       if (typeof p === 'TVector3') { // p is TVector3
         var inv_z;
         p.subtract(this.origin);

         if ((p.z>this.deltaFar.z) || (p.z<this.deltaNear.z)) { return false;};
         inv_z =1/this.deltaFar.z;
         if ((Math.abs(p.x)>(p.z*this.deltaFar.x*inv_z)) || (Math.abs(p.y)>(p.z*this.deltaFar.y*inv_z))) { return false;};
         return true;
       } else { // p is TBox
	 var dyb,dxb;

	 p.move(this.origin.negative());
 	 if ((b.p1.z>this.deltaFar.z) || (b.p2.z<this.deltaNear.z)) { return false;};

 	 if (b.p2.z>this.deltaFar.z) { b.p2.z=this.deltaFar.z;} // if part of back is outside frustum, clip back to frustum

 	 dxb = b.p2.z*(this.deltaFar.x/this.deltaFar.z);
 	 if ((b.p1.x>dxb) || (b.p2.x<-dxb)) { return false;};
 	 dyb=b.p2.z*(this.deltaFar.y/this.deltaFar.z);
	 if ((b.p1.y>dyb) || (b.p2.y<-dyb)) { return false;};

	 return true;
       }
}



function THTML5Game(width,height,depth,overflow_width,overflow_height) {  
   THTML5.call(this,width,height,depth,overflow_width,overflow_height); 

   this.camera = new TCamera();
   this.world = null; //the data structure with game elements info.
};


  extend(THTML5Game,THTML5);

THTML5Game.prototype.createWorld = function(w,h,d) {
 this.world = createArray(w,h,d); // currenlty this is a 3d array, later it will change to octree
};

THTML5Game.prototype.inView = function() {
 // returns a sub array with all the visible elements on the screen view.
};

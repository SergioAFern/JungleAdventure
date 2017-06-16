TShared = function() {
  this.fps=0;
  this.score=0;
  this.lives=3;
  this.won=false;
  this.camera;
// hero fields
  this.state;
  this.direction;
  this.y;
// plane fields

 this.dialog=null; // dialog to display, null if nothing to display

 this.debug=''; // a debug variable to help me debug a web worker

 this.objects = []; // moveable objects on screen
};

var shared = new TShared();


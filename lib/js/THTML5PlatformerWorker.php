<?php
 include_once "TCamera.php";
 include_once "TObjectManager.php";
 include_once "TGridManager.php";
?>
// in order to speed up game execution, we want to run part of the program in parallel
// using a web worker
var Application = null;
function THTML5PlatformerWorker() {  
   this.camera = new TCamera();
   //  internal variables
   this.locked = false;
   Application = this;
   this.player = this.initPlayer();
   this.GridManager = this.initGridManager();
   this.ObjectManager = new TObjectManager(this.initMoveableObjects());
   this.dialogs = this.initDialogs();    
   this.data;
   self.addEventListener('message',function (e) {
     if (Application.locked) return 0;
     Application.locked=true;
     Application.HandleMessage(e);
     Application.locked=false;
   },false);
};

THTML5PlatformerWorker.prototype.HandleMessage = function(e) {
};

// over write this, to define the player region specific to your game.
THTML5PlatformerWorker.prototype.initPlayer = function() {
};

THTML5PlatformerWorker.prototype.initGridManager = function() {
};

// over write this, to define the player region specific to your game.
THTML5PlatformerWorker.prototype.initDialogs = function() {
 return []; // all dialogs to display when player reachers certain regions in the game., play once then remove.
};


//  extend(THTML5Platformer,THTML5);


THTML5PlatformerWorker.prototype.initMoveableObjects = function() {
 return [];
};

THTML5PlatformerWorker.prototype.onCollision = function(camera) {
// check for collision, take action
};


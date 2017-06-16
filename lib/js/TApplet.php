// TApplet
var stActive = 0;
var stInActive = 1;
var stPaused = 2;
var stDead = 3;
var AppletList = [];

var internalID = 0;
  function uniqueID() { 
    return ++internalID;
  };

function TApplet() {
      this.id = uniqueID();
      this.name='';
      this.state = stActive;
      AppletList[this.id]=this;
};

TApplet.prototype.update = function() {
};

TApplet.prototype.render = function() {
};



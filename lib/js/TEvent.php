
var evNothing = 0;
var evMouse   = 1;
var evKeyDown = 2;
var evMessage = 3;

function TEvent(ev) {
  this.what = evNothing;
  this.event;
  if (arguments.length === 1) { 
   this.event = ev;
  };
};

var pending = new TEvent();


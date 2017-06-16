function formatNumber(num,digits) {
  return ("00000"+num).slice(-digits);
};

function floor(x) {return x >>> 0;};

TFPS = function() {
  this.startTime = 0;
  this.frameNumber = 0;
};

TFPS.prototype.getFPS = function() {
  this.frameNumber++; 
  var d = new Date().getTime(),
      currentTime = ( d - this.startTime ) / 1000,
      result = floor( ( this.frameNumber / currentTime ) );

      if( currentTime > 1 ){
	 this.startTime = new Date().getTime();
	this.frameNumber = 0;
      }
   return result;
};

function is_touch_device() {
 return (('ontouchstart' in window) || (navigator.MaxTouchPoints>0) || (navigator.msMaxTouchPoints>0));
};

var mobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
var isTablet = (navigator.userAgent.indexOf('iPad') != -1);

var abs = Math.abs;

TVisualChange = function() {
  this.position = null;
  this.state = null;
  this.transform = null;
};

function copyChanges(obj1,obj2) {
  if (obj1) {
    obj2.position = obj1.position;
    obj2.state = obj1.state;
    obj2.transform = obj1.transform;
  }
};


function createArray(length) {
  var arr = new Array(length || 0), i= length;

  if (arguments.length>1) {
   var args = Array.prototype.slice.call(arguments,1);
   while(i--) arr[length-1-i]=createArray.apply(this,args);
  }

  return arr;
};

function include(file) {
   var http = new XMLHttpRequest();
   http.open('GET',file,false);
   http.send();
   return http.responseText;
};


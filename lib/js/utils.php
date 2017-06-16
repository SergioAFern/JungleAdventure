
 function formatframe(frame) {
  var res ="";
  var range = 1000;
  while (range>=1) {
    if (frame<range) { res+="0";}
    range= range/10;
  };
//  if (frame<100) { res +="0";};
//  if (frame<10) { res +="0";};
  return res+frame;
 };


var fps = {
	startTime : 0,
	frameNumber : 0,
	getFPS : function(){
		this.frameNumber++;
		var d = new Date().getTime(),
			currentTime = ( d - this.startTime ) / 1000,
			result = Math.floor( ( this.frameNumber / currentTime ) );

		if( currentTime > 1 ){
			this.startTime = new Date().getTime();
			this.frameNumber = 0;
		}
		return result;

	}	
};

if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
};

function terminalInsert(txt) {

       var elm = document.getElementById( 'terminal' );
       div = document.createElement( 'div' );
       div.innerHTML = txt;
       elm.appendChild( div );
};

function reload() {
  window.location.href="index.php";
};

function fileExists(url) {
  var http = new XMLHttpRequest();
  http.open('HEAD',url,false);
  http.send();
  return http.status!=404;
};

function include(file) {
   var http = new XMLHttpRequest();
   http.open('GET',file,false);
   http.send();
   return http.responseText;
};

var mobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
var isAndroid = (navigator.userAgent.indexOf('Android') != -1);
var  isTablet = (navigator.userAgent.indexOf('iPad') != -1);
var isiPhone = /iPhone/i.test(navigator.userAgent);

function is_touch_device() {
 return (('ontouchstart' in window) || (navigator.MaxTouchPoints>0) || (navigator.msMaxTouchPoints>0));
};

var css_prefix ='';
if (document.body.style.webkitTransform!=undefined) {
      css_prefix="-webkit-";
      setTransform = function(obj,ani) {
        obj.webkitTransform=ani;
      };
      setTransformOrigin = function(obj,ani) {
        obj.webkitTransformOrigin=ani;
      };
   } else {
      setTransform = function(obj,ani) {
        obj.transform=ani;
      };
      setTransformOrigin = function(obj,ani) {
        obj.transformOrigin=ani;
      };
};

function createArray(length) {
  var arr = new Array(length || 0), i= length;

  if (arguments.length>1) {
   var args = Array.prototype.slice.call(arguments,1);
   while(i--) arr[length-1-i]=createArray.apply(this,args);
  }

  return arr;
};



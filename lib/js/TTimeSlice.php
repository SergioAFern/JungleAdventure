// TTimeSlice version 0.1.0 by Sergio Fernandez August 13, 2015

// this object is used to create a parallele executijng process
// and has basic messeging system between the process and main program
// this object is a replacement for TParallel, when no web worker are available, this fakes, parallel programming

var TimeSlice_ptr;
function TTimeSlice(file) {  // need to use a TParallelProcess manager 
 var jsElm = document.createElement("script");
 jsElm.type="application/javascript";
 jsElm.src=file;
 document.body.appendChild(jsElm);
 TimeSlice_ptr=this;  // this is a temp vartiable, replace wit process manager
};

TTimeSlice.prototype.send = function(txt) {
};


TTimeSlice.prototype.receive = function(e) { 

};

function TParallelComm() { // ParalleleComm object for use with timSlice
};

TParallelComm.prototype.send = function(txt) { 
  TimeSlice_ptr.receive(txt);
};


// TNetworkCommand version 0.1.0 by Sergio Fernandez July 27, 2015
// version 0.1.1 by Sergio Fernandez April 8 2016 --> update to be used acrsso m domains.

// this object is used to communicate back and forth over the network.
// its good for multiple player games that need to communicate over the internet.
// or can even be used to send a command to the server have it perfome some complicated task and retrurn an answer.

// create two classes on using WebSockets, another using XMLHttpRequest.
// first check if webSockets is supproted if so use it, otherwise fall back to XMLHttpRequest.
// also, if using XMLHttpRequest, create 2 versions, one using WebWorkers, and one not using WebWorkers.

function TNetworkCommand(App) {
  this.version = 0; // future version might have additional parameters, so keep a version number
  this.App=App+'.dat'; // set an application name, so that all messages get sent to the correct App.
  this.id = this.command(0,'CREATE',''); // create's a new network ID to identify the person over the network
  this.group=new Array(); // the IDs, on this group list will limit the users you will be communicating with
};

TNetworkCommand.prototype.send = function(user,command,data) {
 return this.command(user,command,data);
};

TNetworkCommand.prototype.broadcast = function(command,data) {
 for(var user in this.group) {
   this.command(user,command,data);
 }
};

TNetworkCommand.prototype.receive = function() {
 // checks if the server has any data for you, if so it will return it.
 // this function should be called from a setInterval, to make sure the client is constatnlty checking teh server for data.
 return this.command(this.id,'GET',''); 
};

TNetworkCommand.prototype.command = function(user,command,data) {
  var http = new XMLHttpRequest();
  http.open('GET','/network.php?version='+this.version+'&Domain='+document.domain+'&App='+this.App+'&sender='+this.id+'&user='+user+'&command='+command+'&data='+data,false);
  http.send();
  return http.responseText;
};

TNetworkCommand.prototype.write = function(variable,data) { // sets the value of a global variable.
 return this.command(variable,'WRITE',data);
};

TNetworkCommand.prototype.read = function(variable) { // gets the value of a global variable.
 return this.command(this.id,'READ',variable);
};

var WebSocketSupported = (window.WebSocket) ? true:false;


// TFindPlayers version 0.1.0 by Sergio Fernandez July 27, 2015

// when you create this class, it will find the requested number of player on the network 
// in a given time frame, any remaming players not found in a given time frame will be replaced
// by a "Computer" player.

function TFindPlayers(comm,timeOut) { // NOTE: currenlty this routine only checks for one player, change code for games that have more than two players
  this.player = ''; // list of players(currenlty onl one player)
  this.poll = 60; // poll server for response 'poll' times
  this.timeOut = 60;
  this.status = "waiting";
  this.comm = comm;

  if (arguments.length === 2) { 
    this.poll = this.timeOut = timeOut;
  }

  var self = this; 
  setTimeout(function() {self.checkRequest()},1000/30); // wait a few second before executing first command, this gives some time for others players to join.
};

TFindPlayers.prototype.checkRequest = function() {
  var self = this;
  var command=this.comm.receive().trim();  // check for messages

  if (command!='') {   // if message has request for player    
   var data = JSON.parse(command); // convert data into a variable
   for (var item in data) {
    if (data[item].command=='SEEK_PLAYER') { 
      this.poll=this.timeOut;
      this.comm.send(data[item].sender,'WANT_TO_PLAY',''); // send response that you want to play
      setTimeout(function() {self.waitForYes()},1000/30); // wait for response
    }
   }
  } else { // if no message, send seek player request
    if (this.poll>0) { // if no message but still in the poll rage, keep on polling
     this.poll=this.poll-1;
     setTimeout(function() {self.checkRequest()},1000/30);
   } else { // no message, and poll time run out, use computer player.
      this.poll=this.timeOut;
      this.comm.send(0,'SEEK_PLAYER',''); // send global(user:0) request
      setTimeout(function() {self.checkResponse()},1000/30); // wait a few second before checking if anyone responded to your request this gives some time for others players to join.
    }
  }
};

TFindPlayers.prototype.checkResponse = function() {
  var self = this;
  var command=this.comm.receive().trim();  // check for messages
  
  if (command!='') { // if message has response from player
   var data = JSON.parse(command); // convert data into a variable
   for (var item in data) {
    if (data[item].command=='WANT_TO_PLAY') { 
      this.poll=this.timeOut;
      this.comm.send(data[item].sender,'YES',''); // acknowledge you got his response.
      setTimeout(function() {self.waitForOK()},1000/30);
    }
   }   
  } else { // if no response, use computer as player
    if (this.poll>0) { // if no message but still in the poll rage, keep on polling
     this.poll=this.poll-1;
     setTimeout(function() {self.checkResponse()},1000/30);
    } else { // no message, and poll time run out, use computer player.
      this.player = 'COMPUTER';
      this.status = 'ready';
   }
  }
};

TFindPlayers.prototype.waitForYes = function() {
  var self = this;
  var command=this.comm.receive().trim();  // check for messages
  
  if (command!='') { // if message has response from player
   var data = JSON.parse(command); // convert data into a variable
   for (var item in data) {
    if (data[item].command=='YES') { 
      this.comm.send(data[item].sender,'OK',''); // acknowledge you got his response.
      this.poll=this.timeOut;
      setTimeout(function() {self.waitForOK()},1000/30);
    }   
   }
  } else { // if no response, use computer as player
    if (this.poll>0) { // if no message but still in the poll rage, keep on polling
     this.poll=this.poll-1;
     setTimeout(function() {self.waitForYes ()},1000/30);
    } else { // no message, and poll time run out, use computer player.
      this.player = 'COMPUTER';
      this.status = 'ready';
    }
  }
};

TFindPlayers.prototype.waitForOK = function() {
  var self = this;
  var command=this.comm.receive().trim();  // check for messages
  
  if (command!='') { // if message has response from player
   var data = JSON.parse(command); // convert data into a variable
   for (var item in data) {
    if (data[item].command=='OK') { 
      this.comm.send(data[item].sender,'OK',''); // acknowledge you got his response.
      this.player=data[item].sender;
      this.status='ready';
      return data[item].sender; // only looking for one player, once found, exit.
    }   
   }
  } else { // if no response, use computer as player
    if (this.poll>0) { // if no message but still in the poll rage, keep on polling
     this.poll=this.poll-1;
     setTimeout(function() {self.waitForOK ()},1000/30);
    } else { // no message, and poll time run out, use computer player.
      this.player = 'COMPUTER';
      this.status = 'ready';
    }
  }
};



var internalAgentID = 0;
  function uniqueAgentID() { 
    return ++internalAgentID;
  };

TAgent = function() {
   this.id = uniqueAgentID();
   this.position = new TVector3();
   this.frame = 0;
};


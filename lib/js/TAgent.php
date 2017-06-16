<?php
 include_once "TVector3.php";
 include_once "TApplet.php";
 include_once "extend.php";
?>

var AgentList = [];

TAgent = function(transition) {
   TApplet.call(this);
   this.origin = new TVector3();
   this.size = new TVector3();
   this.TransitionState=[];
};

extend(TAgent,TApplet); 

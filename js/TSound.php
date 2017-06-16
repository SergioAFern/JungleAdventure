// if device is iPhone or device that coan only play one sound at a time,
// use a sprite sound based class. other wise, use a multisound playing class.

<?php
 include_once "extend.php";
?>


//************************************************************//


TSoundSprite = function(sprite,sounds) { 
 this.active = true; 
 this.sprite = new Audio("audio/"+sprite+((navigator.userAgent.indexOf("Firefox")!=-1)?".ogg":".mp3"));
 this.sprite.volume=0.2;
 this.start = 2;
 this.stop = 2.8;
 this.sounds = sounds;
};

TSoundSprite.prototype.toggleSound = function() { 
  this.active=!this.active;
};

TSoundSprite.prototype.pause= function() {  
  this.sprite.pause();
};

TSoundSprite.prototype.check= function() { 
  if (this.sprite.currentTime>=this.stop) { this.sprite.pause();}
};

TSoundSprite.prototype.play= function(snd) { 
 if (!this.active) return 0;
  this.start=this.sounds[snd].start;
  this.stop=this.sounds[snd].end;

  this.sprite.currentTime=this.start; 
  this.sprite.play();
};

  TSoundManager = TSoundSprite;


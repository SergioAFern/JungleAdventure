TBackGroundAnimationSequence = function(video,frames,ondone) {
  TApplet.call(this);
  //document.write('<img id="'+this.id+'"  style="position:absolute;">');
  this.video = new TSprite(video,frames);
  this.width = this.video.data[0].width;
  this.height = this.video.data[0].height;
  this.bounds = new TBox();

  this.ondone = ondone; /// the function to execute when this applet is finished.

  game.paused=true;
  $(window).load(function() {
    game.paused = false;
      bCanvas.getContext("2d").scale(2,2);
  });
}

 extend(TBackGroundAnimationSequence,TApplet);

TBackGroundAnimationSequence.prototype.update = function() {
  this.video.update();
  if (this.video.frame==this.video.frames) { 
     this.state = stDead;
     this.ondone();
  }
  if(this.bounds.width()!=Screen.bounds.width()) {
    this.bounds.assign(Screen.bounds);
  }
}

TBackGroundAnimationSequence.prototype.render = function() {
      bCanvas.getContext("2d").drawImage(this.video.data[this.video.frame],0,0);
};

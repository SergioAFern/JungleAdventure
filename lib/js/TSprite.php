
TSprite = function(src,frames) {
  TApplet.call(this);
  this.data  = new Array();
  this.frame = 0;
  this.frames= 0;

  // load all images
  for (this.frames=1;this.frames<=frames;this.frames++) {
   var data = new Image();
   data.src = 'sequence/<?=$SYSTEM->subpath();?>'+src+'/'+src+'_'+formatframe(this.frames)+'.png'; 
   this.data.push(data);
  }
  this.frames = this.data.length;
}

 extend(TSprite,TApplet);

TSprite.prototype.update = function() {
  this.frame++;
  if (this.frame>this.frames) this.frame=1;
};




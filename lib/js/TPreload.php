// ohhh that beautiful learning curve.
// this routine, a preloader is ment to deal with the quircks of the javascript language.
// this will not only preload certain items, like images.
// but it will also prevent the garbage collector from deleting the object
// I noticed that if I had a sprite or icon and did not display it for a while,
// the next time I displayed it it would have to reload the image.
// this was due to the garbage collector, which erased it, because it thought it was not being used.
// items preloaded here, will not be delected by garbage collection. :)



function TPreload() {
  this.data = new Array(); // keep items in array to prevent garbage collection from erasing it.
};


TPreload.prototype.image = function(img) {
    temp = new Image();
    temp.src = img;
    this.data.push(temp);
};

TPreload.prototype.images = function(imgArray) {
    for (i=0;i<imgArray.length;++i) {
      temp = new Image();
      temp.src = imgArray[i];
      this.data.push(temp);
   }
};

TPreload.prototype.clear = function() {
   this.data=[];
};


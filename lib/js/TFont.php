var TFont= function(fnt,color) {
      this.fnt = fnt;
      this.color = color;
    };


    TFont.prototype.write = function(txt,pos) {
	  aCanvas.getContext('2d').fillStyle = this.color;
	  aCanvas.getContext('2d').font = this.fnt;
	  aCanvas.getContext('2d').fillText(txt, pos.x, pos.y);
    };

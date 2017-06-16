
function TBar(height,bg) {
      TApplet.call(this);
      Screen.setCanvas(bCanvas);
      bg.render();
      Screen.setCanvas(canvas);
      this.bounds = new TBox();
 
      this.bounds.init(0,bg.y,0,Screen.size.x,bg.y+height,0);
};

    extend(TBar,TApplet);

    TBar.prototype.update = function() {
     };


    TBar.prototype.insert = function(data) {
     document.getElementById(this.id).innerHTML+=data;

    };


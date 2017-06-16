
function TClickRegion(bounds,action) { // this is a clickable region 
      TApplet.call(this);
      this.bounds = new TBox();
      this.action=action; // clicked on this region, then execute this action
 
      this.bounds.assign(bounds);
};

    extend(TClickRegion,TApplet);

    TClickRegion.prototype.update = function() {

       var b = Screen.project(this.bounds);
 
       if (b.contains(Mouse.pos)) {
        fCanvas.style.cursor="pointer";
       };
     };


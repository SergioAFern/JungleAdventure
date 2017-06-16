
function TClickImage(pos,imgUp,imgDown,owner,action) { // this is a clickable region 
      TApplet.call(this);
      this.owner = owner;
      this.imgUp = new Image();
      this.imgDown = new Image();
      this.img = new Image();
      this.action=action; // clicked on this region, then execute this action
      this.bounds = new TBox();

      this.imgUp.src = imgUp;
      this.imgDown.src = imgDown;
      this.img.src = imgUp;

      this.bounds.p2.init(this.imgUp.width,this.imgUp.height,0);
      this.bounds.move(pos);
      this.up(); 
};

    extend(TClickImage,TApplet);

    TClickImage.prototype.update = function() {
       var b = Screen.project(this.bounds);
 
       if (b.contains(Mouse.pos)) {
        fCanvas.style.cursor="pointer";
        if ((Mouse.event==evClick) && b.contains(Mouse.eventPos)) {
          this.owner.clear();
          this.down();       
          Mouse.event=evNothing;
        }
       };
     };

    TClickImage.prototype.up = function() {
       this.bounds.p2.init(this.imgUp.width,this.imgUp.height,0);
       this.bounds.p2.add(this.bounds.p1);

       this.clear();
       this.img.src = this.imgUp.src;
       fCanvas.getContext("2d").drawImage(this.img,this.bounds.p1.x,this.bounds.p1.y);
     };

    TClickImage.prototype.down = function() {
       this.clear();
       this.img.src = this.imgDown.src;
       fCanvas.getContext("2d").drawImage(this.img,this.bounds.p1.x,this.bounds.p1.y);
       this.action();
     };

    TClickImage.prototype.clear = function() {
       fCanvas.getContext("2d").clearRect(this.bounds.p1.x,this.bounds.p1.y,this.bounds.p2.x-this.bounds.p1.x,this.bounds.p2.y-this.bounds.p1.y);
     };


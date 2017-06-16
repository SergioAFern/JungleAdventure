function TScreen(x,y,z) {
       this.size = new TVector3();
       this.target = new TVector3();
       this.bounds = new TBox();

       this.target.init(x,y,z);	
       this.size.init(window.innerWidth,window.innerHeight,1);
       this.bounds.init(0,0,1,x,y,z);

       fCanvas.style.width =bCanvas.style.width =canvas.style.width=x+'px';
       fCanvas.style.height=bCanvas.style.height=canvas.style.height=y+'px';
       fCanvas.style.top   =bCanvas.style.top   =canvas.style.top=0+'px';
       fCanvas.style.left  =bCanvas.style.left  =canvas.style.left=0+'px';
    };

    extend(TScreen,TApplet);


    TScreen.prototype.update = function() {
    };

    TScreen.prototype.render = function() {
    };

    TScreen.prototype.clear = function() {
        canvas.getContext('2d').clearRect(0, 0, this.size.x, this.size.y);
    };

    TScreen.prototype.setCanvas = function(cv) {
      aCanvas = cv;   
    };

    TScreen.prototype.project = function(box) { // scales and translates a point on the canvas 
      box.p1.x=(box.p1.x*(this.bounds.p2.x-this.bounds.p1.x)/this.target.x);
      box.p1.y=(box.p1.y*(this.bounds.p2.y-this.bounds.p1.y)/this.target.y);
      box.p2.x=(box.p2.x*(this.bounds.p2.x-this.bounds.p1.x)/this.target.x);
      box.p2.y=(box.p2.y*(this.bounds.p2.y-this.bounds.p1.y)/this.target.y);
      box.move(this.bounds.p1);
      return box;
    };

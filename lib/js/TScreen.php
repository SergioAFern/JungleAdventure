var sFixedSize = 0;
var sFullScreen = 1;
function TScreen(x,y,z) {
       this.scale = sFixedSize;
       this.size = new TVector3();
       this.target = new TVector3();
       this.bounds = new TBox();

       this.target.init(x,y,z);	
      this.size.init(window.innerWidth,window.innerHeight,1);
      this.bounds.init(0,0,0,window.innerWidth,window.innerHeight,0);

    };

    extend(TScreen,TApplet);


    TScreen.prototype.update = function() {
      this.size.init(window.innerWidth,window.innerHeight,1);
      var factor =1;
      if (this.scale==sFullScreen) {
       factor = (this.size.x)/this.target.x;
       if ((this.size.y/this.target.y)<factor) {
         factor =this.size.y/this.target.y;
       }
     }
     this.bounds.init(0,0,0,this.target.x*factor,this.target.y*factor,0);
     var origin = new TVector3();
     origin.init((this.size.x-this.bounds.p2.x)/2,(this.size.y-this.bounds.p2.y)/2,0);
     this.bounds.move(origin);
    };

    TScreen.prototype.render = function() {
         canvas.style.height=(this.bounds.p2.y-this.bounds.p1.y)+'px';
         canvas.style.top=this.bounds.p1.y+'px';
         canvas.style.left=this.bounds.p1.x+'px';
 
         document.getElementById("background").style.top=document.getElementById("canvas").style.top;
         document.getElementById("background").style.left=document.getElementById("canvas").style.left;
         document.getElementById("background").style.height=document.getElementById("canvas").style.height;
   
         document.getElementById("foreground").style.top=document.getElementById("canvas").style.top;
         document.getElementById("foreground").style.left=document.getElementById("canvas").style.left;
      if (this.scale==sFullScreen) {
         fCanvas.style.width=bCanvas.style.width=canvas.style.width=(this.bounds.p2.x-this.bounds.p1.x)+'px';
         fCanvas.style.height=document.getElementById("canvas").style.height;
      }
    };

    TScreen.prototype.clear = function() {
        canvas.getContext('2d').clearRect(0, 0, this.size.x, this.size.y);
    };

    TScreen.prototype.setCanvas = function(cv) {
      aCanvas = cv;   
    };

    TScreen.prototype.project = function(box) { // scales and translates a point on the canvas 
      var b = new TBox();
      b.p1.x=(box.p1.x*(this.bounds.p2.x-this.bounds.p1.x)/this.target.x);
      b.p1.y=(box.p1.y*(this.bounds.p2.y-this.bounds.p1.y)/this.target.y);
      b.p1.z=0;
      b.p2.x=(box.p2.x*(this.bounds.p2.x-this.bounds.p1.x)/this.target.x);
      b.p2.y=(box.p2.y*(this.bounds.p2.y-this.bounds.p1.y)/this.target.y);
      b.p2.z=0; 
      b.move(this.bounds.p1);
      return b;
    };

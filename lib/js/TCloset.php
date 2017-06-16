var TCloset = function() {
      this.focus = false;
      this.pos = new TVector3();
      this.pos.init(-425,1,1);
    };

    TCloset.prototype = new TApplet();

    TCloset.prototype.constructor = function(bg) { // better safe, redo constructor
      this.focus = false;

    };

    TCloset.prototype.update = function() {
      var h = window.innerHeight;
      this.pos.y=(Screen.size.y-380)/2;
      if (this.focus!=0) {
       if (this.pos.x<0) {
        this.pos.x+=25;
       }
      } else {
       if (this.pos.x>-425) {
        this.pos.x-=25;
       }
      }
    
    };

    TCloset.prototype.render = function() {
      document.getElementById("closet").style.top=this.pos.y+'px';
      document.getElementById("closet").style.left=this.pos.x+'px';
    };


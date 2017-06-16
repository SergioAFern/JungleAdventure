function TAppletApplication() {
      this.applets = [];
      this.paused = false;
      this.onpaused = function(){};
    }

    TAppletApplication.prototype.update = function() {
     // update all applets
      var remove = new Array();
      var i;
      for (i=0; i<this.applets.length; ++i) {
       switch(this.applets[i].state) {
         case stActive: this.applets[i].update();
                        break;
         case stDead: remove.push(this.applets[i]);
                       break;
        }
      }
     // remove dead objects
      for (i=0;i<remove.length;++i) {
        this.removeApplet(remove[i]);
      }

    };

    TAppletApplication.prototype.render = function() {
    // render all applets
       var i;
       for (i=0; i<this.applets.length; ++i) {
         if (this.applets[i].state != stInActive) {
          this.applets[i].render();
         }
       }
    };

    TAppletApplication.prototype.addApplet = function(app) {

     this.applets[this.applets.length]=app; // try using this.applets.push(app);
    };

    TAppletApplication.prototype.removeApplet = function(app) {
    var Applet = null;
     var i;
     var j;
     if (typeof app === 'string') {
       // app is a string 
       for (i=0; i<this.applets.length;++i) {
        if (this.applets[i].name == app) {
         Applet = this.applets[i];
         break;
        }
       }
     } else {
     // app is a TApplet
       for (i=0; i<this.applets.length;++i) {
        if (this.applets[i] == app) {
         Applet = this.applets[i];
         break;
        }
       }
     }

       if (Applet != null) {
//          Applet.run();
          delete Applet;

          for (j=i; j<(this.applets.lenth-1);++j) {
           this.applets[j]=this.applets[j+1];
          }

        this.applets.length -=1;
       }
    };

    TAppletApplication.prototype.doRun = function() {

    };

    TAppletApplication.prototype.run = function() {
        fCanvas.style.cursor="";
      if (!this.paused) {
       this.update();
       this.doRun();
       this.render();
      } else { 
       this.onpaused(); 
      }
    };

    TAppletApplication.prototype.getEvent = function() {
      var ev = new TEvent();
      // check for mouse event
      if (Mouse.event != evNothing) {
        ev.what=evMouse;
        Mouse.event=evNothing;
      }
      return ev;
    };

//***********************************************

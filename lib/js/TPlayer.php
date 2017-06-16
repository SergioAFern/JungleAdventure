var TPlayer = function(name,bounds,url) {
      this.parent = new TAgent(); // inherited
      this.name = name;
      this.angle = 0; // from 0..360 to rotate player around.
      this.pause = 0; // dont do animation in certain situations, like rotate. for a few cycles.
      this.bounds = new TBox();

     this.bounds = bounds;  
     
    
    terminalInsert(" <div id='_"+name+"' style='position:absolute; top: "+bounds.p1.y+"px; left: "+bounds.p1.x+"px;'>"
                        + "<a href='"+url+"'>"
                        + '<img src="sequence/'+name+'/STAND/0/'+name+'_0001.png" border="0" id="'+name+'" onmouseover="'
                        + "showarrow('menu-"+name+"');"
                        + '" onmouseout="hidearrow('
                        + "'menu-"+name+"');"
                        + '"></a></div>');

    };

    extend(TPlayer,TAgent);


    TPlayer.prototype.render = function() {

      var temp = new Image();
      var name = this.name;

      var angle = this.angle;
      if (temp.addEventListener) { // preload images if you can
        temp.addEventListener("load",function(){document.getElementById(name).src = temp.src;},true); // show image after loaded
        temp.src = 'sequence/'+this.name+'/'+this.parent.state+'/'+angle+'/'+this.name+'_0'+formatframe(this.parent.frame)+'.png'; 
      } else {
       document.getElementById(this.name).src = 'sequence/'+this.name+'/'+this.parent.state+'/'+angle+'/'+this.name+'_0'+formatframe(this.parent.frame)+'.png';
      }

     document.getElementById(this.name).height=(this.bounds.p2.y-this.bounds.p1.y)*this.bounds.p1.z;


     if (this.pause>0) {
       this.pause--;
     } else {
       this.parent.frame++;
       this.bounds.move(this.parent.speed);
       var x = this.bounds.p1.x+parseInt(document.getElementById("canvas").style.left)
       var y = this.bounds.p1.y+parseInt(document.getElementById("canvas").style.top)
       document.getElementById('_'+name).style.left = x+ 'px';
       document.getElementById('_'+name).style.top =  y+ 'px';

       document.getElementById('menu-'+name).style.left = x+ 'px';
       document.getElementById('menu-'+name).style.top = y+ 'px';

       if (this.parent.frame>30) { 
         this.parent.frame=1;
         this.changeState();

       };
     }
    };

    TPlayer.prototype.changeState = function() {
         this.parent.speed.init(0,0,0);
         this.parent.state=this.parent.movement.transitionState[this.parent.state];
    }

    TPlayer.prototype.rotate = function(deg) {
        this.pause = 30;
        this.angle+=deg;
        if (this.angle>359) { this.angle-=360;};
        if (this.angle<0)   { this.angle+=360;};
    };


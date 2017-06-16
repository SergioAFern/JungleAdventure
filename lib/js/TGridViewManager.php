<?php
 include_once "TTileView.php";
?>

function TGridViewManager(ScreenSize,TileSize,stateMapping) { 
  TView.call(this);
  this.origin = new TVector3();
  this.grid  = createArray(Math.floor(ScreenSize.y/TileSize.y)+4,Math.floor(ScreenSize.x/TileSize.x)+4);
  this.TileSize = TileSize;
  this.old_origin = new TVector3(); 
  this.initialized = false;
 
 for(i=0;i<this.grid.length;i++) { //y
    for(j=0;j<this.grid[i].length;j++) { //x
      this.grid[i][j] = new TTileView({x:(j*TileSize.x),y:(i*TileSize.y)},this.TileSize,stateMapping);
    }
  }

 };

extend(TGridViewManager,TView);

TGridViewManager.prototype.draw = function() {
  var HTML='';
  for(i=0;i<this.grid.length;i++) {
    for(j=0;j<this.grid[i].length;j++) {
      HTML+=this.grid[i][j].draw();
    }
  };
  return TView.prototype.draw.call(this,HTML);
};

TGridViewManager.prototype.init = function(grid, camera) {
  this.origin.init(camera.x,0,0);
  //this.moveTo(camera.x,0);
  var offset = camera.x % this.TileSize.x;
  var length=this.grid.length;
  for(i=0;i<length;i++) {//y
    var length2=this.grid[i].length;
    for(j=0;j<length2;j++) { //x
//alert('sergio:'+i+' '+j);
      this.grid[i][j].moveTo(j*this.TileSize.x-offset,i*this.TileSize.y-80-512);
      if (grid[i][j]==0) { 
          if (this.grid[i][j].state!=0) {this.grid[i][j].hide(); }
       } else { 
         if (this.grid[i][j].state==0) { this.grid[i][j].show(); }
      };
         this.grid[i][j].setState(grid[i][j],1)
       //('fine');
    }
  };
  this.old_origin.x=camera.x;
};

TGridViewManager.prototype.HideBlank = function() {
 // check for changes only update changed grids.
  for(i=0;i<this.grid.length;i++) {//y
    for(j=0;j<this.grid[i].length;j++) { //x
     if (this.grid[i][j].state==0)  
       this.grid[i][j].hide();
    }
  };
};

TGridViewManager.prototype.shiftGrid = function(delta) {
   var temp,length=this.grid[0].length;

 var length2=this.grid.length;
 for(k=0;k<Math.abs(delta);k++) {
  if (delta>0) {
    for(i=0;i<length2;i++) { 
      this.grid[i].push(this.grid[i].shift());
      temp = this.grid[i][length-1];
      temp.moveTo(temp.origin.x+length*this.TileSize.x,temp.origin.y); 
   };
  } else {
     for(i=0;i<length2;i++) { 
        this.grid[i].unshift(this.grid[i].pop());
        temp=this.grid[i][0];
        temp.moveTo(temp.origin.x-length*this.TileSize.x,temp.origin.y);
     };
  }
 };
};

TGridViewManager.prototype.update = function(grid,camera) {
  if (this.old_origin.x==camera.x) return;

  var delta = new TVector3(camera.x-this.old_origin.x,0,0); 

  if (Math.abs(delta.x)>this.TileSize.x) { 
    var columns_to_shift = Math.floor(delta.x/this.TileSize.x);
    if (columns_to_shift<grid[0].length) {
       this.shiftGrid(columns_to_shift);
       this.old_origin.x=camera.x;
    };
  };
  this.init(grid,camera);
  if (!this.initialized) { 
    this.initialized=true;
    this.HideBlank();
  };

};


TGridViewManager.prototype.showgrid = function(camera) {
 var html=camera.position;
  for(i=0;i<this.grid.length;i++) {
    html+=' [';
    for(j=0;j<this.grid[i].length;j++) {
      html+=this.grid[i][j].state+',';
    }; 
  }; 
 console.log(Math.floor(camera.position/256)+' '+html);
};

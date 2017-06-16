function TGridManager(ScreenSize,TileSize,level) {
  this.origin = new TVector3();
  this.grid  = createArray(Math.floor(ScreenSize.y/TileSize.y)+4,Math.floor(ScreenSize.x/TileSize.x)+4);
  this.level;
  this.TileSize = TileSize;

  this.load(level);
 };


TGridManager.prototype.load = function(level) {
  this.level = level;//createArray(level.length,level[0].length);
 };

TGridManager.prototype.update = function(camera) {
  // NOTE: must camera as the center of the screen, and draw +-3 grids from the center vertically and horizontaly.
  this.onCollision();
  var offset = Math.floor(camera.origin.x / this.TileSize.x);
  var length=7;// NOTE: height is 7 grids high. // ORIGINAL CODE: this.grid.length;
  for (i=0;i<length;i++) { //y
    var length2=this.grid[i].length;
    for(j=0;j<length2;j++) { //x
      this.grid[i][j]=this.level[i][offset+j-3];
    }
   }
};

TGridManager.prototype.onCollision = function() {
if (hero.checkpoint!=null) {return 0;};
var start = Application.camera.current.origin.x>>8;
var offset = -(Application.camera.current.origin.x & 255);

if (hero.position.y==220)  {

 switch(this.level[3][start]) {
     case 2: //if (shared.camera.offset>-220) 
              hero.fall(); break;
     case 3: case 4: case 5: case 6: if (hero.state!=8) hero.drown();  break;
     case 18:  Score(100); 
     case 17:
          Score(100); 
          this.level[3][start]=1; 
          this.grid[3][3]=1; 
          break;
     case 12:  youWon(); break;
     case 14: case 16:  hero.die(); break; 
   }

};

if (hero.position.y>220)  {
    switch(this.level[4][start]) {
     case 18:  Score(100); 
     case 17:
          Score(100); 
          this.level[4][start]=1; 
          this.grid[4][3]=0; 
          break;
     case 12:  youWon(); break;
     case 7:if (((offset>-128) && (hero.direction==-1)) || ((offset<-128) && (hero.direction==1))) return 0;
            hero.stop(); 
            break;
      case 3: case 16:  hero.die(); break; 
   }
 };
};

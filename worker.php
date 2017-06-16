<?php 
 include_once "js/util.php";
 include_once "lib/js/THTML5PlatformerWorker.php";
 include_once "js/data.php";
 include_once "lib/js/TBox.php";
 include_once "js/TAgent.php";
 include_once "js/TEnemy.php";
 include_once "js/TPlayer.php";
 include_once "js/shared.php";
?>


function TMyGame() {  
   THTML5PlatformerWorker.call(this);
};

  extend(TMyGame,THTML5PlatformerWorker);

// over write this, to define the player region specific to your game.
TMyGame.prototype.initPlayer = function() {
  return new TPlayer();
};

TMyGame.prototype.initGridManager = function() {
  return new TGridManager({x:960,y:640},{x:256,y:256},eval(include('level/jungle.txt')));
};

// over write this, to define the player region specific to your game.
TMyGame.prototype.initDialogs = function() {
 return [{region:{x:1250,y:200,w:100,h:100},class:'start',duration:15000,
             dialog:[{start:200,end:3200,text:'hello? hello!'},
                     {start:3350,end:5240,text:'cant get a signal.'},
                     {start:5325,end:7240,text:'GPS still works.'},
                     {start:7445,end:12460,text:'Pick up point is 6 miles in that direction.'}]}
        ,{region:{x:191600,y:400,w:100,h:100},class:'artifact',duration:15000,
             dialog:[{start:200,end:3200,text:'What now!'},
                     {start:3355,end:5240,text:'I cant believe this!'},
                     {start:5325,end:12460,text:'Its a Giant Human Skeleton!'}]}
         ,{region:{x:293800,y:200,w:100,h:100},class:'finish',duration:15000,
             dialog:[{start:200,end:3200,text:'Nothing Brings you more joy,'},
                     {start:3300,end:7240,text:'than seeing your family.'},
                     {start:7445,end:12460,text:'The End.'}]}]; 
};


TMyGame.prototype.HandleMessage = function(e) {
  switch(e.data) {
    case 'upback': this.player.upback(); break;
    case 'up': this.player.up(); break;
    case 'upforward': this.player.upforward(); break;
    case 'backward': this.player.backward(); break;
    case 'stop': this.player.stop(); break;
    case 'forward': this.player.forward(); break;
    case 'downback': this.player.downback(); break;
    case 'down': this.player.down(); break;
    case 'downforward': this.player.downforward(); break;
    case 'start':setInterval(Render,1000/30); break;
    case 'pause': shared.dialog=null; this.player.stop(); break;
    case 'resume':  // NOTE: place code to clear last dialog message
                 break;
    default: 
             shared.state=this.player.state;
             shared.direction=this.player.direction;
             shared.y=this.player.position.y;

             self.postMessage(JSON.stringify(shared)); 
  }
};

TMyGame.prototype.initMoveableObjects = function() {
 var objects = [];
 // this will replace build Enemy List
  var length = this.GridManager.level[2].length;
  for (var i=0;i<length;++i) {
     switch (this.GridManager.level[2][i]) {
       case 19:// eagle 
                objects.push(new TEagle(i*256,-120)); // enemy list entry
                // first 5 buts is character index, bits above 5, indicate. enemy array index value
                this.GridManager.level[2][i]= 0; 
               break;
     }
   }
  for (var i=0;i<length;++i) {
     switch (this.GridManager.level[3][i]) {
       case 15:// Lion
                objects.push(new TLion(i*256,280)); // enemy list entry
                // first 5 buts is character index, bits above 5, indicate. enemy array index value
                this.GridManager.level[3][i]= 1; 
               break;
     }
   }
 return objects;
};

var game = new TMyGame();

function setUp(spawn) { 
  game.camera.slide({x:spawn,y:0,z:0});
}; 

function move() {  
  if (((game.camera.current.origin.x>(5*256)) && (game.player.speed.x<=0)) || ((game.player.speed.x>=0))) {
      game.camera.current.origin.add(game.player.speed);
  } else {game.player.acceleration.x=0;}
};


var hero = game.player;

var oldposition =-1;

function Update() {

  move();
  playerUpdate();

  game.ObjectManager.update();

  if (Application.camera.current.origin.x!=oldposition) {
      oldposition=Application.camera.current.origin.x;
      game.GridManager.update(Application.camera.current);
  }
};

function Render() { 
  for(var i=0; i<game.dialogs.length;i++) {
    if ((game.player.position.x>game.dialogs[i].region.x) && (game.player.position.y>game.dialogs[i].region.y) &&
        (game.player.position.x<(game.dialogs[i].region.x+game.dialogs[i].region.w)) && (game.player.position.y<(game.dialogs[i].region.y+game.dialogs[i].region.h)))  { 
          shared.dialog = game.dialogs[i];
          if (i==(game.dialogs.length-1)) shared.won=true;
          game.dialogs.splice(i,1);
      break;
    };
  };

var cameraview = new TBox(Application.camera.current.origin.x-640,0,-50,
                          Application.camera.current.origin.x+1216,640,50);

shared.objects=game.ObjectManager.inView(cameraview);
shared.grid=game.GridManager.grid;

shared.camera = Application.camera.current.origin;

             shared.state=game.player.state;
             shared.direction=game.player.direction;
             shared.y=game.player.position.y;

             self.postMessage(JSON.stringify(shared)); 
  };

// Initialize
setUp(256*5);
setInterval(Update,1000/30);

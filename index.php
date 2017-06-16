<!DOCTYPE html>
<html>
<head>
<meta id="viewport" name="viewport" content="minimal-ui,width=device-width,height=device-height,user-scalable=no,initial-scale=1,maximum-scale=1,user-scalable=0"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
<link rel="stylesheet" type="text/css" href="js/player.css"/>
<link rel="stylesheet" type="text/css" href="js/level.css"/>
<link rel="stylesheet" type="text/css" href="js/dialog.css"/>
<link rel="stylesheet" type="text/css" href="js/moveable.css"/>
<style>
.box {
 width:145px; height:52px;  padding-top:15px; font-family:ariel; color:white; font-size:24pt; text-shadow: 2px 2px black; background: url(image/box.png);
}
</style>
<link href='http://fonts.googleapis.com/css?family=Bangers' rel='stylesheet' type='text/css'>
</head>
<body bgcolor="#000000" style="font-family:Bangers,cursive; color:white; font-size:22pt; text-shadow: 4px 4px black; overflow:hidden; image-rendering:optimizeSpeed;">
<script src="lib/js/ui.php"></script>
<script>

<?php
 include_once "lib/js/utils.php";
 include_once "lib/js/THTML5Platformer.php";
 include_once "lib/js/platformer/state/human.php";
 include_once "lib/js/TNetworkCommand.php";  
?>

function TSplashScreen() {
  var data = JSON.parse(include("level/global.lvl"));
  TGroup.call(this,[new TImage({x:0,y:0},data["splash"]),
                    new TSpeedButton({x:data["start"][0],y:data["start"][1]},'image/box.png','init();'),
                    new TLabel({x:(data["start"][0]+10),y:(data["start"][1]+10)},language.get("Start"),'init();',40)]);
};

extend(TSplashScreen,TGroup);

TSplashScreen.prototype.draw = function() {
  return TView.prototype.draw.call(this,TGroup.prototype.draw.call(this));
};

var _start = new TSplashScreen();

// *************** TDiv ***************
function TDiv(pos,size,_style) {
    TView.call(this);
    this.origin = pos;
    this.size=size;
    this._style=_style; 
};

  extend(TDiv,TView);


TDiv.prototype.style = function() { 
  return TView.prototype.style.call(this)+'top:'+this.origin.y+'px; left:'+this.origin.x+'px; width:'+this.size.x+'px; height:'+this.size.y+'px;'+this._style;
};

// **********************************************

<?php
 include_once "THighScoreDialog.php"; 
 include_once "TKeyboard.php"; 
?>

var keyboard = new TKeyboard('HighScoreDialog.keyboard');

function TMyGame(w,h,d) { 
   THTML5Platformer.call(this,w,h,d,256,0); // add and extra bit to the left and right of the game.
};


  extend(TMyGame,THTML5Platformer);


TMyGame.prototype.style = function() { 
  return TGroup.prototype.style.call(this)+'top:0px; left:0px; width:1472px; height:'+this.size.y+'px; font-family:ariel; color:white; font-size:24pt; text-shadow: 2px 2px black; background: url(image/jungle_back.jpg);';
};

TMyGame.prototype.initPlayer = function() {
  var infinite =' 800ms steps(24, end) infinite';
  var once = ' 800ms steps(23) forwards';
  return new TTileView({x:676,y:220,z:0},{x:125,y:200,z:0},
             [{class:'hero_stand',states:[{sStand:'stand'+infinite},{sWalk:'walk'+infinite},{sRun:'run'+infinite},{sCrouch_stand:'crouch_stand'+infinite},{sCrouch_walk:'crouch_walk'+infinite}]}
             ,{class:'hero_jump',states:[{sJump:'jump'+once},{sFall:'fall'+once},{sSwing:'swing'+infinite},{sDrown:'drown'+once},{sDie:'die'+once}]}
             ,{class:'hero_crouch',states:[{sCrouch_die:'crouch_die'+infinite},{sCrouch_jump:'crouch_jump'+infinite},{sCave_exit:'cave_exit'+once}]}]);
};

var sNone   = 0;
var sGround = 1;
var sHole   = 2;
var sSnake2 = 3;
var sQuickSand = 5;
var sWall    = 7;
var sCrash00 = 8;
var sCrash10 = 9;
var sCrash20 = 10;
var sRescue00 = 11;
var sRescue10 = 12;
var sRescue20 = 13;
var sSnake = 14;
var sAlligator = 16;
var sCash = 17;
var sMedievil = 18;
var sSkeleton = 22;
var sCrash01 = 28;
var sCrash11 = 29;
var sCrash21 = 30;
var sRescue01 = 31;
var sRescue11 = 32;
var sRescue21 = 33;

TMyGame.prototype.initGrid = function() {
  var infinite =' 800ms steps(11, end) infinite';
  return new TGridViewManager(this.size,new TVector3(256,256,0),
             [{class:'other',states:[{sNone:'_none'+infinite},{sCash:'cash'+infinite},{sSkeleton:'skeleton'+infinite},{sWall:'wall'+infinite},{sHole:'hole'+infinite}]},
              {class:'fixed',states:[{sSnake:'snake'+infinite},{sSnake2:'snake2'+infinite},{sAlligator:'alligator'+infinite},{sQuickSand:'quicksand'+infinite}]},
              {class:'crash',states:[{sCrash00:'crash_00'+infinite},{sCrash10:'crash_10'+infinite},{sCrash20:'crash_20'+infinite},{sCrash01:'crash_01'+infinite},{sCrash11:'crash_11'+infinite},{sCrash21:'crash_21'+infinite},
                                     {sRescue00:'rescue_00'+infinite},{sRescue10:'rescue_10'+infinite},{sRescue20:'rescue_20'+infinite},{sRescue01:'rescue_01'+infinite},{sRescue11:'rescue_11'+infinite},{sRescue21:'rescue_21'+infinite},
                                     {sGround:'ground'+infinite},{sMedievil:'medieval'+infinite}]}]);
};


TMyGame.prototype.initMoveable = function() {
 var HTML='';
  for(i=0;i<140;i++) {
    HTML+='<div id="m'+i+'" style="display:none;"></div>'
  };
  return new TWrapper(HTML);
};

debug=false;
THTML5Platformer.prototype.HandleMessage = function(e) {
 if ((shared.won) || (lives==0)) return 0;
  shared = JSON.parse(e.data);

if (shared.debug.length>2) alert(shared.debug+' '+shared.lives+' '+lives+' '+shared.state);

if (shared.lives<lives) {
  sound.play('die');
  lives=shared.lives;
  LIVES.style.width=lives*32+'px';
};

  if (lives==0) {
    gameOver();
  };

//  if (debug) alert(e.data);
//LIVES.innerHTML=shared.debug;
if (shared.dialog!=null) { 
   game.worker.postMessage("pause");
   MessageDialog.show();
};



  game.gridManager.update(shared.grid,shared.camera);
  //alert(shared.grid[2]);
  game.player.moveTo(676,shared.y);
  game.player.setState(shared.state,shared.direction);

  if (shared.camera.x!=shared.oldposition) {
    var offset = -(shared.camera.x & 255);

    setTransform(SCREEN,['translate3d(',offset,'px,0,0)'].join(''));

    GROUND.backgroundPosition=(offset % 64)+'px';

    TREE.backgroundPosition=-(((2/3)*shared.camera.x) % 256)+'px';
    TREE2.backgroundPosition=-(((1/3)*shared.camera.x) % 263)+'px';

    shared.oldposition=shared.camera.x;
    game.objectManager.draw(shared.objects);


}

 if (shared.score!=oldscore) {
    SCORE.innerHTML=shared.score;
    if (shared.score>oldscore) {sound.play('points');} else {sound.play("die");}
 }; 
 oldscore=shared.score;
// remove shared won, for the last item in teh list of dialogs
 if (shared.won) { 
   game.worker.postMessage("pause");
   sound.play('won');
 };

};


var _tree2,_tree,_ground;

TMyGame.prototype.InitDeskTop = function() {
  DeskTop = THTML5Platformer.prototype.InitDeskTop.call(this);
  
  var data = JSON.parse(include("level/global.lvl"));
  
  _tree2 = new TDiv({x:0,y:-90},{x:1472,y:384},'background: url(image/tree2.png);');
  _tree = new TDiv({x:0,y:0},{x:1472,y:330},'background: url(image/tree.png);');
  _ground = new TDiv({x:0,y:423},{x:1472,y:64},'background: url('+data["ground"]+');');
  _timer = new TDiv({x:1060,y:570},{x:145,y:67},'background: url(image/box.png); text-align: center; padding-top:14px;');
   DeskTop.list.unshift(_tree2,_tree,new TWrapper(include('content.php')));
   DeskTop.list.push(_ground,_timer,HighScoreDialog,keyboard);
  return DeskTop;
};


TMyGame.prototype.preload = function() { 
 // besides a preload add an idle load, to speed the starting up of the game
 // that is, only load the needed images, then images that wont be needed until later on in the game
 // can be loaded slowly on the background while the game plays.
 return ['image/start.jpg','sprite/crash.png','sprite/fixed.png','sprite/other.png','sprite/hero_stand.png','sprite/hero_crouch.png','sprite/hero_jump.png','sprite/moveable.png','image/sound.png','image/jungle_back.jpg','sprite/start.jpg','sprite/finish.jpg'];
};

TMyGame.prototype.bodyStyle = function() { 
  return THTML5.prototype.bodyStyle(this)+
         "font-family:Bangers,cursive; color:white; font-size:22pt; text-shadow: 4px 4px black;"; 
};

TMyGame.prototype.postContent = function() {
 return _start.draw()+THTML5.prototype.postContent.call(this);
};

var _Sound,_lives;

TMyGame.prototype.InitMenuBar = function() {
  _lives = new TDiv({x:80,y:20},{x:96,y:26},'background: url(image/heart.png);');
  MenuBar = THTML5Platformer.prototype.InitMenuBar.call(this);
  MenuBar.size.x+=100;
  MenuBar.list.push(_lives);
  return MenuBar;
};

TMyGame.prototype.InitStatusBar = function() {
  _Sound = new TSpeedButton({x:100,y:0},'image/sound.png','_sound();');
  return new TMenuBar({x:256,y:560},{x:180,y:80},[
               new TSpeedButton({x:0,y:0},'image/help.png','help();'),
               _Sound]);
};

var game = new TMyGame(960,640,10);

var TIMER = document.getElementById("view"+_timer.id);
TIMER.innerHTML='59:59';;
var START = document.getElementById("view"+_start.id);


function help() { 
 game.worker.postMessage("pause");
 START.innerHTML="<img src='image/instructions.jpg'><div style='font-family:Bangers,cursive; color:white; font-size:24pt;position:absolute; top:60px; left:270px; width:380px; text-align:left;'>"+language.get("To win the game, you must reach the plane before time runs out."); 
 START.innerHTML+="<div style='font-family:Bangers,cursive; color:white; font-size:24pt;text-shadow: 2px 2px black; position:absolute; top:320px; left:270px; width:340px; text-align:left;'>"+language.get("Pick up lost objects from the floor to get points."); 
 START.innerHTML+="<div style='font-family:Bangers,cursive; color:white; font-size:24pt;text-shadow: 2px 2px black; position:absolute; top:60px; left:850px; width:340px; text-align:left;'>"+language.get("To move your player<br>Click/Touch on the screen in the direction you want the player to move."); 
 START.innerHTML+="<div style='font-family:Bangers,cursive; color:white; font-size:24pt;text-shadow: 2px 2px black; position:absolute; top:380px; left:920px; width:140px; text-align:center;'>"+language.get("Controls"); 
 START.innerHTML+="<div style='font-family:Bangers,cursive; color:white; font-size:24pt;text-shadow: 2px 2px black; position:absolute; top:575px; left:1050px; width:140px; text-align:center;'>"+language.get("Play")+"</div><a href='javascript:Continue();'><img src='image/null.png' border='0' width='1472' height='640' style='position:absolute; top:2px; left:2px;'></a>"; 
 _start.show();
 window.scrollTo(0,0);
};

function Restart() {
  game.worker.terminate();
  location.reload();
};

function gameOver() {
 clearInterval(clockId);
 HighScoreDialog.show();
};


function _sound() { // tuggle sound
 sound.toggleSound();
 document.getElementById('view'+_Sound.id).src=["image/",sound.active?"":"no","sound.png"].join("");
};


function Continue(){
  _start.hide();
};

<?php 
 include_once "js/util.php"; 
 include_once "js/TCamera.php";
 include_once "js/shared.php";
 include_once "js/TSound.php";
?>

var sound = new TSoundManager("jungle",{'points':{start:2,end:2.8},'die':{start:0,end:1.7},'won':{start:4,end:7}});

var time = 60*60; 

var SCREEN = document.getElementById("screen").style;
var GROUND = document.getElementById("view"+_ground.id).style;
var WALL = document.getElementById("wall");
var TREE = document.getElementById("view"+_tree.id).style;
var TREE2 = document.getElementById("view"+_tree2.id).style;
var SCORE = document.getElementById("score");
//var DEBUG = document.getElementById("debug");
var LIVES = document.getElementById("view"+_lives.id);


function clock() { 
  if (shared.checkpoint==null) {
    time-=1;
    TIMER.innerHTML=floor(time/60)+':'+formatNumber(floor(time%60),2);
    if (time<=0) {
       gameOver();
    }
  }
};

var oldscore = 0;


var lives = 3;

sound.sprite.addEventListener('play',function() {
  sound.pause();
  sound.sprite.removeEventListener('play',arguments.callee,false);
},false);

sound.sprite.addEventListener('timeupdate',function() {
  sound.check();
},false);


function init() { 
// init audio
 
   sound.sprite.play();
    _start.hide();
 
  clockId=setInterval(clock,1000);
  game.worker.postMessage("start");
};

</script>
</body>
</html>

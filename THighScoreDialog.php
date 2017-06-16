// *************** THighScoreDialog ***************
function THighScoreDialog() {
    TDialog.call(this,{x:490,y:400});
    this.flags = 0;
    this.list.push(new TAnimationDiv({x:50,y:55},{x:384,y:256},'restart'));
    this.TopScoresName = [];
    this.TopScores =[];
    for(i=0;i<10;i++) {
      this.TopScoresName[i] = new TStaticText({x:80,y:(64+i*24)},{x:400,y:80},'');
      this.TopScores[i] = new TStaticText({x:360,y:(64+i*24)},{x:400,y:80},0);
      this.list.push(this.TopScoresName[i],this.TopScores[i]);
    };
    this.list.push(new TWrapper('&nbsp; GAME OVER!<input style="position:absolute; top:68px; left:64px; width:280px; height:14px;" id="yourscore">'),
                   new TStaticText({x:30,y:40},{x:400,y:80},'Enter your name next to your score.'),
                   new TSpeedButton({x:80,y:320},'image/box.png','AppletList['+this.id+'].restart();'),
                   new TLabel({x:130,y:340},'ReStart','AppletList['+this.id+'].restart();'),
                   new TSpeedButton({x:280,y:320},'image/box.png','AppletList['+this.id+'].quit();'),
                   new TLabel({x:340,y:340},'Exit','AppletList['+this.id+'].quit();'));

};

  extend(THighScoreDialog,TDialog);


THighScoreDialog.prototype.style = function() { 
  return TGroup.prototype.style.call(this)+'top:120px; left:500px; width:'+this.size.x+'px; height:'+this.size.y+'px; font-family:ariel; color:white; font-size:24pt; text-shadow: 2px 2px black; display:none;';
};

THighScoreDialog.prototype.restart = function() { 
  if (this.index<10) {
    this.scores[this.index].name=document.getElementById("yourscore").value;
    this.comm.write('scores',JSON.stringify(this.scores));
  };
 document.location.reload();
};

THighScoreDialog.prototype.quit = function() { 
  if (this.index<10) {
    this.scores[this.index].name=document.getElementById("yourscore").value;
    this.comm.write('scores',JSON.stringify(this.scores));
  };
  document.location.href="/";
};

THighScoreDialog.prototype.show = function() {
  this.comm = new TNetworkCommand('jungle'); //create communication 
  var data = this.comm.read('scores'); // read "scores" variable

  this.scores=[];
  if (data.trim()=='') {this.scores=[{name:'',score:0},{name:'',score:0},{name:'',score:0},{name:'',score:0},{name:'',score:0},{name:'',score:0},{name:'',score:0},{name:'',score:0},{name:'',score:0},{name:'',score:0}];} // if variable is not declared, set it all to zeros.
  else { this.scores=eval(data);};
  this.index=99; 

  for(i=0;i<10;i++) { // if your in the top 10 score, put your score, and remember its index location.
    if (this.scores[i].score<shared.score) {
      for (j=9;j>i;j--) {
        this.scores[j].name=this.scores[j-1].name;
        this.scores[j].score=this.scores[j-1].score;
      };
   
      this.scores[i].name='';
      this.scores[i].score=shared.score;
      this.index=i; 
      break;
    }
  };
  for(i=0;i<10;i++) {
    document.getElementById("view"+this.TopScoresName[i].id).innerHTML=this.scores[i].name;
    document.getElementById("view"+this.TopScores[i].id).innerHTML=this.scores[i].score;
  };

  game.worker.terminate();
  document.getElementById("view"+this.id).style.display="block";
  if (this.index<10) {
    document.getElementById("view"+this.id).style.left="300px";
    document.getElementById("yourscore").style.top=(68+this.index*24)+'px'; 
    keyboard.show();
  } else {
    document.getElementById("yourscore").style.visibility='hidden';
  };
};

THighScoreDialog.prototype.keyboard = function(key) { 
 if (key=='enter') {
    document.getElementById("view"+this.id).style.left="500px";
 } else if (key=='undo') {
    value=document.getElementById("yourscore").value;
    value=value.slice(0,-1);
    document.getElementById("yourscore").value=value;
 } else {
    value=document.getElementById("yourscore").value;
    value=value+key;
    document.getElementById("yourscore").value=value;
 }
};

var HighScoreDialog = new THighScoreDialog();


// **********************************************


function TKeyboard(callerObj) {
    TDialog.call(this,{x:416,y:498});
    this.flags = 0;
    this.callerObj = callerObj;

    this.list.push(new TSpeedButton({x:2,y:2},'image/keyA.png',this.callerObj+'("A");'),
		   new TSpeedButton({x:84,y:2},'image/keyB.png',this.callerObj+'("B");'),
		   new TSpeedButton({x:166,y:2},'image/keyC.png',this.callerObj+'("C");'),
		   new TSpeedButton({x:248,y:2},'image/keyD.png',this.callerObj+'("D");'),
		   new TSpeedButton({x:330,y:2},'image/keyE.png',this.callerObj+'("E");'),
new TSpeedButton({x:2,y:84},'image/keyF.png',this.callerObj+'("F");'),
		   new TSpeedButton({x:84,y:84},'image/keyG.png',this.callerObj+'("G");'),
		   new TSpeedButton({x:166,y:84},'image/keyH.png',this.callerObj+'("H");'),
		   new TSpeedButton({x:248,y:84},'image/keyI.png',this.callerObj+'("I");'),
		   new TSpeedButton({x:330,y:84},'image/keyJ.png',this.callerObj+'("J");'),
new TSpeedButton({x:2,y:166},'image/keyK.png',this.callerObj+'("K");'),
		   new TSpeedButton({x:84,y:166},'image/keyL.png',this.callerObj+'("L");'),
		   new TSpeedButton({x:166,y:166},'image/keyM.png',this.callerObj+'("M");'),
		   new TSpeedButton({x:248,y:166},'image/keyN.png',this.callerObj+'("N");'),
		   new TSpeedButton({x:330,y:166},'image/keyO.png',this.callerObj+'("O");'),
new TSpeedButton({x:2,y:248},'image/keyP.png',this.callerObj+'("P");'),
		   new TSpeedButton({x:84,y:248},'image/keyQ.png',this.callerObj+'("Q");'),
		   new TSpeedButton({x:166,y:248},'image/keyR.png',this.callerObj+'("R");'),
		   new TSpeedButton({x:248,y:248},'image/keyS.png',this.callerObj+'("S");'),
		   new TSpeedButton({x:330,y:248},'image/keyT.png',this.callerObj+'("T");'),
new TSpeedButton({x:2,y:330},'image/keyU.png',this.callerObj+'("U");'),
		   new TSpeedButton({x:84,y:330},'image/keyV.png',this.callerObj+'("V");'),
		   new TSpeedButton({x:166,y:330},'image/keyW.png',this.callerObj+'("W");'),
		   new TSpeedButton({x:248,y:330},'image/keyX.png',this.callerObj+'("X");'),
		   new TSpeedButton({x:330,y:330},'image/keyY.png',this.callerObj+'("Y");'),
new TSpeedButton({x:2,y:412},'image/keyZ.png',this.callerObj+'("Z");'),
		   new TSpeedButton({x:84,y:412},'image/key.png',this.callerObj+'(" ");'),
		   new TSpeedButton({x:166,y:412},'image/undo.png',this.callerObj+'("undo");'),
                   new TSpeedButton({x:300,y:420},'image/return.png',this.callerObj+'("enter");AppletList['+this.id+'].hide();'),
                   new TLabel({x:330,y:430},'Enter',this.callerObj+'("enter");AppletList['+this.id+'].hide();'));
};

  extend(TKeyboard,TDialog);


TKeyboard.prototype.style = function() { 
  return TGroup.prototype.style.call(this)+'top:40px; left:796px; width:'+this.size.x+'px; height:'+this.size.y+'px; font-family:ariel; color:white; font-size:24pt; text-shadow: 2px 2px black; display:none;';
};

TKeyboard.prototype.restart = function() { 
  if (this.index<10) {
    this.scores[this.index].name=document.getElementById("yourscore").value;
    this.comm.write('scores',JSON.stringify(this.scores));
  };
 document.location.reload();
};

TKeyboard.prototype.quit = function() { 
  if (this.index<10) {
    this.scores[this.index].name=document.getElementById("yourscore").value;
    this.comm.write('scores',JSON.stringify(this.scores));
  };
  document.location.href="/";
};

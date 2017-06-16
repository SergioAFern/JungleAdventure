<?php
 include_once "TView.php";
 include_once "TSpeedButton.php";
?>

function TFrame(size) {  
  TView.call(this);
  this.icons=[new TSpeedButton({x:size.x-52,y:10},'/image/close.png','AppletList['+this.id+'].owner.close();')];
  this.size=size;
};

extend(TFrame,TView); //  inherit 

TFrame.prototype.draw = function() { // draw complete view
  var icons;
  if ((this.owner.flags && wfClose)>0) icons=this.icons[0].draw();

  return TView.prototype.draw.call(this,
        '<div style="position:absolute; top:28px; left:0px; background-image:url(\'/image/window-l.png\'); width:32px; height:'+(this.size.y-60)+'px;"></div>'
        +'<div style="position:absolute; top:0px; left:0px; background-color:#ffbd31; width:'+(this.size.x-4)+'px; height:'+(this.size.y-11)+'px; box-shadow: 5px 5px 5px #000000;border-radius: 10px; border:1px solid black;"></div>'
        +'<div id="title'+this.owner.id+'" style="position:absolute; top:12px; left:20px; background-color:#000000; color:#ffffff; height:40px; white-space:nowrap;">&nbsp;'+this.owner.title+'&nbsp;&nbsp;</div>'+icons
    );
};



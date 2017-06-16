<?php
 include_once "TGroup.php";
 include_once "extend.php";
?>

var sbHorizontal = 0;
var sbVertical = 1;

function TScrollBar(owner,AOptions) {  
   TGroup.call(this,[]);
   this.value;
   this.min;
   this.max;
   this.PgStep;
   this.ArStep;
   this.owner = owner;
   this.options = AOptions;
   if (AOptions==sbHorizontal) {
     this.size.init(owner.size.x-76,38,owner.size.z);
     this.origin.init(10,owner.size.y-60,owner.origin.z);
     this.list.push(new TSpeedButton({x:10,y:6},'/image/scrollbar_cursor_horizontal.png','help();','" dragabble="true'));
   } else {
     this.size.init(38,owner.size.y-76-30,owner.size.z);
     this.origin.init(owner.size.x-60,50,owner.origin.z);
     this.list.push(new TSpeedButton({x:6,y:10},'/image/scrollbar_cursor_vertical.png','help();'));
   };
};


extend(TScrollBar,TGroup);

TScrollBar.prototype.draw = function() { // draw complete view
  return TView.prototype.draw.call(this,
        '<div style="position:absolute; top:0px; left:0px; background-color:#37abff; width:'+this.size.x+'px; height:'+this.size.y+'px; box-shadow: 5px 5px 5px #000000;border-radius: 10px; border:1px solid black;">'
        +TGroup.prototype.draw.call(this)+'</div>');
};

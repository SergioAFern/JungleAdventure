<?php
// include_once "TView.php";
?>
function TTileView(origin,size,stateMapping) {  
  TView.call(this);
  this.origin.assign(origin);
  this.size.assign(size);  
  this.stateMapping = stateMapping;
  // setup a quick way to look yup the class an aniation based on the state
  this.quickMap = [];
  this.state=0; // current state
  this.direction=0;

  for(_class=0;_class<stateMapping.length;_class++) {
    for(_state=0;_state<stateMapping[_class].states.length;_state++) {
      this.quickMap[eval(Object.keys(stateMapping[_class].states[_state])[0])]={class:'',animation:''};
      this.quickMap[eval(Object.keys(stateMapping[_class].states[_state])[0])].class=stateMapping[_class].class;
      this.quickMap[eval(Object.keys(stateMapping[_class].states[_state])[0])].animation=eval('stateMapping['+_class+'].states['+_state+'].'+Object.keys(stateMapping[_class].states[_state])[0]);
    };
  };
};

extend(TTileView,TView);

TTileView.prototype.style = function() { 
  return TView.prototype.style.call(this)+'top:'+this.origin.y+'px; left:'+this.origin.x+'px; width:'+this.size.x+'px; height:'+this.size.y+'px;';
};

TTileView.prototype.draw = function(content) {
  if (content===undefined) var content='';
  var html='';
  for(_class=0;_class<this.stateMapping.length;_class++) {
     html+='<div style="position:absolute; top:0px; left:0px; display:'+((_class==0)?'block':'none')+';" class="'+this.stateMapping[_class].class+'" id="view'+this.id+'_'+this.stateMapping[_class].class+'" >'+content+'</div>';
  };
  return TView.prototype.draw.call(this,html);
};

TTileView.prototype.setState = function(state,direction) { //alert(state+this.state);

// NOTE: to add, if state = 999, then set entire, tile to display none
 if (this.state!=state) {
    // NOTE: need one more bit of code... the direction
    if (this.quickMap[state].pointer==undefined) { 
      this.quickMap[state].pointer = document.getElementById("view"+this.id+'_'+this.quickMap[state].class).style;
//      alert('popo out');
    };
    //alert('maria: '+this.state);
    if (this.quickMap[this.state].class!=this.quickMap[state].class) {
       //alert('maria');
       if (this.quickMap[this.state].pointer==undefined) { 
         this.quickMap[this.state].pointer = document.getElementById("view"+this.id+'_'+this.quickMap[this.state].class).style;
       };
     this.quickMap[state].pointer.display='block';
     this.quickMap[this.state].pointer.display='none';
    };
  this.state=state;
  this.setAnimation(this.quickMap[state].pointer,this.quickMap[state].animation);
  
 };
if (this.direction!=direction) { // this will replace change.ranformation
  this.direction=direction;
  this.transform("scaleX("+this.direction+")");
}
};


TTileView.prototype.reSize = function(state) { 
 if (this.state!=state) {
  // NOTE: put code here, hide last class, show new class, and set state animation.
  if (this.quickMap[this.state].class!=this.quickMap[state].class) {
   document.getElementById("view"+this.id+'_'+this.quickMap[state].class).style.display='block';
   document.getElementById("view"+this.id+'_'+this.quickMap[this.state].class).style.display='none';
  };
  this.state=state;
 };
};

TTileView.prototype.move = function(state) { 
 if (this.state!=state) {
  // NOTE: put code here, hide last class, show new class, and set state animation.
 };
};

TTileView.prototype.moveTo = function(x,y) {
// replace with Translate or Translate3d for better performace on cell phones tablets.
 if (this.origin.x!=x) {
   if (this.pointer==undefined) { 
      this.pointer = document.getElementById("view"+this.id).style;
   };
   this.pointer.left=x+'px';
   this.origin.x=x;
 };

if (this.origin.y!=y) {
   if (this.pointer==undefined) { 
      this.pointer = document.getElementById("view"+this.id).style;
   };
   this.pointer.top=y+'px';
   this.origin.y=y;
 };
};

if (document.body.style.webkitTransform!=undefined) {
      TTileView.prototype.transform = function(ani) {
   if (this.pointer==undefined) { 
      this.pointer = document.getElementById("view"+this.id).style;
   };
        this.pointer.webkitTransform=ani;
      };
      TTileView.prototype.setAnimation = function(obj,ani) { 
        obj.webkitAnimation=ani;
      };
   } else {
      TTileView.prototype.transform = function(ani) {
   if (this.pointer==undefined) { 
      this.pointer = document.getElementById("view"+this.id).style;
   };
        this.pointer.transform=ani;
      };
      TTileView.prototype.setAnimation = function(obj,ani) { 
        obj.animation=ani;
      };
};


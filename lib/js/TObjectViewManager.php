<?php
  include_once "TObjectView.php";
?>

function TObjectViewManager() {
  TView.call(this);
  this.list = [];
  this.views = []; // List of Visible objects on screen
};


TObjectViewManager.prototype.draw = function(list) {
  var length=list.length;
  for(i=0; i<length;i++) {
  // (1) get List
  // (2) if item in list already in view list, then update position & animation, and set View display to 'block'.
  // (3) if item in list in not in view array, then add itemt to list, and display it on the screen.
   var index = list[i];
   if (this.views[index.id]===undefined) {
    this.list[index.id]={id:index.id,speed:{x:index.speed.x},visible:true,direction:1};
    var views = this.views[index.id]= document.getElementById('m'+index.id);
    views.className=index.class;
    views.style.display='block';
    views.style.position='absolute';
    views.style.left=Math.floor(index.origin.x-shared.camera.x+663)+'px';
    views.style.top=index.origin.y+'px';
    views.style.width=index.size.x+'px';
    views.style.height=index.size.y+'px';  
   } else {
     this.views[index.id].style.left=(index.origin.x-shared.camera.x+663)+'px';
     if (index.direction!=this.list[index.id].direction) {
	 this.list[index.id].direction*=-1;
         setTransform(this.views[index.id].style,'scaleX('+index.direction+')');
      }
      if (!this.list[index.id].visible) {
        this.list[index.id].visible=true;
        this.views[index.id].style.display='block';
      }
   };
  // (4) any item in the view list, which is not in the list, hide it by setting the display to 'none'
  };
  // hide none visible elements
  global_list=list;
  global_views=this.views;
  // dont go through all elements, its slow. just keep a list of last visible items, and update that.
  this.list.forEach(
   function entry(entry) {
     var length=global_list.length;
     for(i=0;i<length;i++) {
       if (entry.id==global_list[i].id) return;
     };
      // if you get to this point set item invisible.
     if (entry.visible) { 
        entry.visible=false;
        global_views[entry.id].style.display='none';
     }
   }
);
};


function TObjectManager(list) {
  this.list = list;
};

TObjectManager.prototype.inView = function(bounds) {
 var inview = [];
 var item_bound = new TBox();
  // come up with  a faster search.
  var length=this.list.length;
  for(i=0;i<length;i++) {
   // if item in view then add to inview list
   item_bound.init(this.list[i].origin.x,this.list[i].origin.y,-50,
                   this.list[i].origin.x+this.list[i].size.x,this.list[i].origin.y+this.list[i].size.y,50);
   if (!item_bound.intercept(bounds).isEmpty()) inview.push(this.list[i]);
  };
 return inview;
};

TObjectManager.prototype.update = function() { 
  // update all items in the list
  var length=this.list.length;
  for(i=0;i<length;i++) {
   this.list[i].update();
  };
};

TObjectManager.prototype.insert = function(entities) {
};

TObjectManager.prototype.delete = function(entity_id) {
};


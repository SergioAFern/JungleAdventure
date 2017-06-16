TItem = function() {
}

TItemAgent = function() {
}

extend(TItemAgent,TItem);

TItemPlayer = function() {
  this.icon = new TImage();
  this.lives = 5;
}

extend(TItemPlayer,TItemAgent);

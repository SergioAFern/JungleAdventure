function TOctree() {  
   this.bounds = new TBox();
   this.data;
   this.quadrant = createArray(2,2,2);
   this.parent;
};

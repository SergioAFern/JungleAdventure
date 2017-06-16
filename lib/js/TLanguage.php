TLanguage = function(lang) { 
  this.lang;
  this.english = []; // base language
  this.dest =[]; // target language
 
  this.english = this.load('en');
  this.setLanguage(lang);
};

var supportedLanguages = ["en","es"];

TLanguage.prototype.load = function(lang) { 
  var http = new XMLHttpRequest(); 
  http.open('GET','text/data.'+lang,false); 
  http.send(); 
  return eval(http.responseText);
};

TLanguage.prototype.setLanguage = function(lang) {
  if (supportedLanguages.indexOf(lang)==-1) lang="en";

  this.dest = this.load(lang);
};

TLanguage.prototype.get = function(ctx) { // convert from english to current language
 return this.dest[this.english.indexOf(ctx)]
};

var _language = window.navigator.userLanguage || window.navigator.language;

var language = new TLanguage(_language.slice(0,2));


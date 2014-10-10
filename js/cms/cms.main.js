var CMS = CMS || {};

(function(){
	// JS templates settings
	
	// Main function
	var main = function(){
		// start application

		CMS.trigger('init.app');
	};
	
	var alibs = [
			["jquery-1.8.0.min.js",  "underscore.min.js", "backbone.min.js", "bootstrap.min.js"],
			
			["jquery.cms.js", "cms.models.base.js",

			"cms.models.js", "cms.events.js", 

			"cms.views.base.js", "cms.views.js"]
		]
	  , alen = alibs.length;
	
	var getloadfunc = function(i){
		var libs = alibs[i]
		  , func;
		  
		if (i === alen-1) {
			func = function(){
				CMS.load(libs, main);
			};
		} else {
			func = function(){
				CMS.load(libs, getloadfunc(i+1));
			};
		}
		return func;
	};
	
	getloadfunc(0)();
	
	
	// Console
	// -------
	// Shortcuts for internal development functions.
	// Beware - IE doesn't allow binding to "console" object.
	
	if (window.navigator.userAgent.indexOf("MSIE") >= 0
		|| typeof console === "undefined") {
		window.clog = function(){};
		window.cdir = function(){};
		window.ctime = function(){};
	} else {
		window.clog = function(){
			return console.log.apply(console, arguments);
		};
		window.cdir = function(){
			return console.dir.apply(console, arguments);
		};
		window.ctime = function(){
			var start = null;
			return function(str){
				return;
				if (str === "start") {
					start = (new Date()).getTime();
					clog('started');
				} else {
					clog(str, (new Date()).getTime() - start);
				}
			};
		}();
	}
	
}());

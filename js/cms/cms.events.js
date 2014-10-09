var CMS = CMS || {};

if (_) {
	_.templateSettings = {
		interpolate: /\[\[([\s\S]+?)\]\]/g,
		evaluate: /\[\!([\s\S]+?)\]/g,
		escape: /\[-([\s\S]+?)\]/g
	};
	_.extend(CMS, Backbone.Events);
}

/**
 * KITCHEN
 * Event catalog.
 * 
 */
(function(){
	
	// Events container - shortcut
	var cms = {};

	// Initialized views
	var views = {};


	// App events
	// ----------
	// Some major App-related events.

	// initialize App - kitchen application model
	cms.init_app = function(url) {
		var cms = this;
		
		$.get( typeof url === "undefined" ? "http://sitecms.intra/" : url, function( data ) {
			
			$( "[data-sitecontainer]" ).hide("slow", function(){
				$( "[data-sitecontainer]" ).html( data );
				$("[data-sitecontainer]").off("click").off("mouseover").off("mouseout");
				$("[data-article]").off("mouseover").off("mouseout");
				$("[data-sitecontainer] a").off("click");
				$( "[data-sitecontainer]" ).show("slow");
			});;
			
			$("[data-sitecontainer]").on("mouseover", "[data-article]", function(e){
				$(e.currentTarget).css({"outline": "3px solid #000"});
				$(e.currentTarget).parent().append('<button style="position: absolute; bottom: 0px; right: 0;" class="btn" data-addarticle><i class="glyphicon glyphicon-search"></i></button>');
			}).on("mouseout", "[data-article]", function(e){
				$(e.currentTarget).css({"outline": "none"});
				$("[data-addarticle]").remove();
			});
			
			$("[data-sitecontainer]").on("click", "a", function(e){
				var url = $(e.target).attr("href");
				cms.trigger("init.app", url);
				return false;
			});
		});
	};
	
	// App internationalization
	cms.init_i18n = function(lang, done) {
		var kcn = this
		  , done = typeof done === "function" ? done : function(){};
		  
		if (kcn.Settings.ActiveLanguage === lang) {
			done();
		} else {
			kcn.Settings.ActiveLanguage = lang;
			kcn.ajax("/cms/configs/lang-"+lang+".json", {}, function(data){
				kcn.i18n = data;
				done();
			});
		}
	};

	// Bind all events to namespace
	_(cms).each(function(func,key){
		var str = key.split("_").join(".");
		CMS.bind(str, function(){
			try {
				func.apply(CMS, arguments);
			} catch (e) {
				console.log(1);
			}
		});
	});

})();
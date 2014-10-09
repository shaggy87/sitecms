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
	
	var pageChangeFinished = true;


	// App events
	// ----------
	// Some major App-related events.

	// initialize App - kitchen application model
	cms.init_app = function(url) {
		var cms = this;
		var $site = $("[data-sitecontainer]");
		cms.trigger("change.page");
		
		$("a", $site).live("click", function(e){
			var url = $(e.target).attr("href");
			cms.trigger("change.page", url);
			return false;
		});
		console.log(4);
		$("body").bview("PagesSelectDropdown", {});
	};
	
	cms.change_page = function(url) {
		if (pageChangeFinished) {
			pageChangeFinished = false;
			$.get( typeof url === "undefined" ? "http://sitecms.intra/" : url, function( data ) {
				
				$( "[data-sitecontainer]" ).hide("slow", function(){
					$( "[data-sitecontainer]" ).html(data);
					$( "[data-sitecontainer]" ).show("slow", function(){
						pageChangeFinished = true;
					});
				});;
				
				$("[data-sitecontainer]").on("mouseover", "[data-article]", function(e){
					$(e.currentTarget).css({"outline": "3px solid #000"});
					$(e.currentTarget).parent().append('<button style="position: absolute; bottom: 0px; right: 0;" class="btn" data-addarticle><i class="glyphicon glyphicon-search"></i></button>');
				}).on("mouseout", "[data-article]", function(e){
					$(e.currentTarget).css({"outline": "none"});
					$("[data-addarticle]").remove();
				});
				
			});
		} else {
			return false;
		}
	}
	
	// Views
	// -----

	// Adding and removing views and subViews
	
	// Add view
	// XXB 1e 1jquery
	cms.view_add = function(view, viewName) {
		if ( typeof views[viewName] === "undefined" ) {
			views[viewName] = [];
		}
		views[viewName].push(view);
		if (view.isModal_ === true) {
			this.App.get("modals").push( view );
		}
		if (view.drop_ === true) {
			this.trigger("modal.launch", "ModalBackdrop");
		}
	}
	
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
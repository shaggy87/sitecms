/**
 * KITCHEN
 * List of editor "views" (small "controllers" of actions).
 * @requires CMS.editor.views.base
 */
(function(){
	
	var views = CMS.views = CMS.views || {};

	// local, extendable View
	var KitchenView = views.KitchenView;
	
	// sscope - scope View functions to get jQuery scope element
	var sscope = function(func){
		return _.compose(
			func,
			function(e){
				return $(e.target);
			}
		);
	};
	
	// Shortcut function to extend events
	var extevents = function(parent, options){
		return _.extend({}, parent.prototype.events, options);
	};

	var strip_tags = function(input){
		// making sure the allowed arg is a string containing 
		// only tags in lowercase (<a><b><c>)
	  	var allowed = (((allowed || "") + "")
			.toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
		var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
			commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

		return input
			.replace(commentsAndPhpTags, '')
			.replace(tags, function ($0, $1) {
				return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
			});
	};


	// UI views
	// --------

	//Pages - select dropdown
	views.PagesSelectDropdown = views.DropdownGeneric.extend({
		events: extevents( views.DropdownGeneric, {
			"click .dropdown-menu a[data-page]":     "gotoPage",
			"click .page-edit":                      "pageEdit",
			"click .page-new":                       "pageNew"
		}),

		postRender_: function(){
			
		},
		
		
	});
	
	//Pages - select dropdown
	views.ModalGeneric = views.ModalGeneric.extend({
		events: extevents( views.DropdownGeneric, {
			"click .dropdown-menu a[data-page]":     "gotoPage",
			"click .page-edit":                      "pageEdit",
			"click .page-new":                       "pageNew"
		}),

		postRender_: function(){
			clog(9);
		},
		
		
	});
	
	//Pages - select dropdown
	views.PagesMenu = views.DropdownGeneric.extend({
		events: extevents( views.DropdownGeneric, {
			"click [data-pages]":    "pagesDialog"
		}),

		postRender_: function(){
			
		},
		
		pagesDialog: function(){
			clog(1);
		}
		
	});
	
	//Pages - select dropdown
	views.StylesMenu = views.DropdownGeneric.extend({
		events: extevents( views.DropdownGeneric, {
			"click [data-pages]":    "pagesDialog"
		}),

		postRender_: function(){
			
		},
		
		pagesDialog: function(){
			clog(1);
		}
		
	});
	
	//Pages - select dropdown
	views.AddMenu = views.DropdownGeneric.extend({
		events: extevents( views.DropdownGeneric, {
			"click [data-pages]":    "pagesDialog"
		}),

		postRender_: function(){
			
		},
		
		pagesDialog: function(){
			clog(1);
		}
		
	});
	
	//Pages - select dropdown
	views.SettingsMenu = views.DropdownGeneric.extend({
		events: extevents( views.DropdownGeneric, {
			"click [data-pages]":    "pagesDialog"
		}),

		postRender_: function(){
			
		},
		
		pagesDialog: function(){
			clog(1);
		}
		
	});
	
})();
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
	}


	// UI views
	// --------

	//Pages - select dropdown
	views.PagesSelectDropdown = views.DropdownGeneric.extend({
		events: extevents( views.DropdownGeneric, {
			"click .dropdown-menu a[data-page]":     "gotoPage",
			"click .page-edit":                      "pageEdit",
			"click .page-new":                       "pageNew"
		}),

		//Re-render dropdown on Pages change/add
		watchModelChanges: _.once(function(self){
			self.model.bind('change', self.render, self);
			self.model.bind('add', self.render, self);
		}),

		postRender_: function(){
			console.log(2);
			_.defer(_.bind(function(){
				this.adjustMenuHeight();
			},this));
		},
		
		arr: [],
		postRender: function(){
			var view = this
			  , multiMenu;

			view.watchState();
			view.arr = [];
			view.watchModelChanges( view );
			view.$(".sortableNested").nestedSortable({
				handle: 'div',
				items: 'li',
				tolerance: 'pointer',
				toleranceElement: '> div',
				revert: 250,
				listType: 'ol',
				maxLevels: 2,
				opacity: .6,
				placeholder: "ui-state-highlight",
				forcePlaceholderSize: true,
				stop: function(){
					multiMenu = view.$(".sortableNested").nestedSortable(
						"toHierarchy",
						{startDepthCount: 0}
					);
					view.arr = [];
					CMS.trigger('pages.ordered', view.makeArrFromItems(multiMenu));
				}
			});
			view.adjustMenuHeight();
			view.stateRestore();
		},

		//Get height for menu pages
		menuHeight: 420,
		adjustMenuHeight: function(){
			var menu_height = this.$(".shown-pages li div").height();
			var page_height = ($(window).height() - this.menuHeight) / 2;
			var hidden_menu_height = this.model.countVisible() * menu_height;
			var shown_menu_height = this.model.countInternal() * menu_height;
			if (shown_menu_height < page_height) {
				page_height = page_height + (page_height - shown_menu_height);
				this.$(".hiden-pages").css("max-height", page_height);
			} else if (hidden_menu_height < page_height) {
				page_height = page_height + (page_height - hidden_menu_height);
				this.$(".shown-pages").css("max-height", page_height);
			} else if (page_height < 50) {
				this.$(".hiden-pages").css("max-height", "50px");
				this.$(".shown-pages").css("max-height", "50px");
			} else {
				this.$(".hiden-pages").css("max-height", page_height);
				this.$(".shown-pages").css("max-height", page_height);
				//INFO $(".shown-pages", this.el) equivalent to above
			}
		},

		adjustLoaderHeight: function(){
			var cur_pos = this.$('.shown-pages').scrollTop();
			this.$('.savingPages').css("top", cur_pos + "px");
		},

		makeArrFromItems: function(mm, parrent){
			var view = this
			  , arr = view.arr
			  , parrent = typeof parrent === "undefined" ? "" : parrent + ".";
			
			_.each(mm, function(val, key){
				key = key + 1;
				if (typeof val !== "undefined") {
					arr.push({id: val.id, rb: parrent + key});
					if (typeof val.children !== "undefined") {
						view.makeArrFromItems(val.children,  key);
					}
				}
			});
			return arr;
		},

		// goto page in site being edited
		gotoPage: sscope(function($el){
			if (typeof $el.attr('data-page') === "undefined") {
				// clicked on i element
				CMS.trigger('page.gotopage', $el.parent().attr('data-page'));
			} else {
				CMS.trigger('page.gotopage', $el.attr('data-page'));
			}
		}),

		// edit page settings
		pageEdit: sscope(function($el){
			CMS.trigger("page.edit", $el.attr('data-uid'));
		}),

		// new page dialog
		pageNew: function(){
			CMS.trigger("page.addnew");
			CMS.unbind("page.added");
			CMS.bind("page.added", _.once(function(page){
				CMS.trigger("page.gotopage", page.get("Id"));
				CMS.trigger("page.edit", page.get("Id"));
			}));
		}
		
	});
	
})();
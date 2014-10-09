/**
 * KITCHEN
 * List of base, extendable, editor views.
 * 
 */
(function(){
	
	var views = CMS.views = CMS.views || {};
	

	// KitchenView
	// -----------

	// Core view
	views.KitchenView = Backbone.View.extend({
		template: null,
		model: null,

		// Last data rendered
		data_: null,

		// Is this view "modal" popup?
		isModal_: false,       
		
		// Does this view have "backdrop" dark background
		drop_: false,
		
		// Is this view/modal "draggable"
		draggable_: false,
		
		
		/**
		 * Custom render function, supports asynchronous rendering of view.
		 * @param {Object|Function} model  Model, options or callback
		 */
		render: function(){
			var data = {lng: CMS.i18n}
			  , postRenderFn = function(){}
			  , callbackFn = function(){}
			  , arg0 = arguments[0];
			
			/// Passing parameters
			// simple object
			if (typeof arg0 === "object") {
				// object - leave option to pass model as :model object key
				if (typeof arg0.model !== "undefined") {
					this.model = arg0.model;
					delete arg0.model;
				}
				if (_.keys(arg0).length > 0) {
					_.extend(data, arg0);
				}
			}
			// callback
			if (typeof arg0 === "function") {
				callbackFn = arg0;
				_.extend(data, this.data_);
			}
			// passing model - directly
			if (this.model !== null) {
				_.extend(data, {
					mdata: this.model.toJSON()
				});
			}

			/// Post-rendering function(s)
			
			// one-time postRender trigger
			postRenderFn = _.bind(this.postRenderBase, this);
			
			// subViews - add nested view(s)
			if (typeof this.subViews === "object") {
				postRenderFn = _.compose(
					_.bind(this.renderSubViews, this),
					postRenderFn
				);
			}
			
			// postRender - executes after every render
			if (typeof this.postRender === "function") {
				postRenderFn = _.compose(
					_.bind(this.postRender, this),
					postRenderFn
				);
			}
			
			/// Rendering callback
			// 1st - actual view rendering
			// 2nd - postRender
			// 3rd - final callback (pass View instance as first param)
			callbackFn = _.compose(
				_.bind(callbackFn, window, this),
				postRenderFn,
				_.bind(function(html){
					//FIXME try to improve handling template exceptions
					try {
						$(this.el).html( _.template(html, data) );
					} catch (e) {
						CMS.trigger("exception", e);
					}
					this.data_ = data;
				}, this)
			);

			CMS.template(this.template, callbackFn);
			return this.el;
		},


		// Unbind all events on this instance
		unbindAll: function(){
			CMS.unbind(null, null, this);
		},
		
		// Extend this function in views to prevent memory leaks
		onRemove: function(){
			//no-op here
		},
		
		
		/// Rendering

		// Render views per defined 'subViews' options. 'this.el' is needed,
		// so do this only after view's been rendered.
		renderSubViews: function(){
			var rfunc = _.bind(function(val, key){
				var ak = key.split(" ");
				val.apply(this, [ this.$(ak[0]), ak[1] ]);
			}, this);
			_.each(this.subViews, rfunc);
		},
		
		// Base postRender function, contains first-time render triggers.
		postRendered_: false,
		postRenderBase: function(){
			if (this.draggable_ === true) {
				this.makeDraggable();
			}
			if (!this.postRendered_ && typeof this.postRender_ === "function") {
				_.defer(_.bind(this.postRender_, this));
				this.postRendered_ = true;
			}
			//TODO...
		},
		
		
		/// State changes
		
		// Turning watching on/off
		stateDaemonId: null,
		watchState: function(){
			var mdl = new CMS.models.ViewState();
			var func = _.bind(this.stateWatch, this, mdl);
			this.stateDaemonId = setInterval(func, mdl.get("DaemonInterval"));
			this.stateRestore = _.bind(this.stateRestore, this, mdl);
		},

		unwatchState: function(){
			if (this.stateDaemonId === null) return;
			clearInterval( this.stateDaemonId );
		}
	});
	
	var KitchenView = views.KitchenView;
	
	
	// App UI
	// ------

	// Application views.
	
	// TODO move App views from this file

	//AppMain view - initial application UI
	views.AppMain = KitchenView.extend({
		events: {
			"change .image-upload-form input[name=file]":   "uploadImageChanged",
			"change .file-upload-form input[name=file]":   "uploadFileChanged",
			"click .logout-link":        "userLogout"
		},
		
		subViews: {
			".app-overlay AppMenu": function($el, v){
				//FIXME remove MainMenu model - not needed?
				$el.bview(v, new CMS.models.MainMenu());
			}
		},

		postRender_: function(){
			CMS.trigger("app.inited");
		},
		
		postRender: function(){
			this.model.set("iframe", this.$("#main-iframe").get(0));
			this.model.set("el", this.$(".app-overlay").get(0));
		},
		
		// Filename changed - image upload happens now
		uploadImageChanged: function(e){
			var $inp = $(e.target)
			 , validate = [
			  	"png",
			  	"jpg",
			  	"gif",
			  	"jpeg"
			  ]
			  , ext = $inp.val().split(".");
			
			if ($inp.val() === "") return;
			ext = ext[ext.length - 1].toLowerCase();
			if (_.indexOf(validate, ext) === -1) {
				CMS.trigger("error.display", CMS.msg('notSupportedImageFormat'), CMS.msg('error'));
				return;
			}
			CMS.trigger("loading", true);

			// Register callback for iframe upload form
			CMS.unbind("image.uploaddone");
			CMS.bind("image.uploaddone", _.once(_.bind(function(data){
				if (data.status) {
					CMS.trigger("error.display", data.error);
				} else {
					var title = data.data.uploaded.split("/");
					title = title[title.length - 1];
					CMS.trigger("image.add", {
						Title: title,
						Path: data.data.uploaded
					});
				}
				CMS.trigger("loading", false);
			}, this)));
			
			// Submit form data and reset FILE field
			this.$(".image-upload-form").submit();
			$inp.val('');
		},
		
		// Filename changed - image upload happens now
		uploadFileChanged: function(e){
			var $inp = $(e.target)
			  , validate = [
			  	"doc",
			  	"docx",
			  	"pdf",
			  	"ppt",
			  	"pptx",
			  	"xls",
			  	"xlsx",
			  	"csv",
			  	"txt",
			  	"odt"
			  ]
			  , ext = $inp.val().split(".");
			
			if ($inp.val() === "") return;
			ext = ext[ext.length - 1].toLowerCase();
			if (_.indexOf(validate, ext) === -1) {
				CMS.trigger("error.display", CMS.msg('notSupportedFileFormat'), CMS.msg('error'));
				return;
			}
			CMS.trigger("loading", true);

			CMS.unbind("file.uploaddone");
			CMS.bind("file.uploaddone", _.once(_.bind(function(data){
				if (data.status) {
					CMS.trigger("error.display", data.error);
				} else {
					var title = data.data.uploaded.split("/")
					  , ext
					  , ttl;
					title = title[title.length - 1];
					ttl = title;
					ext = ttl.split(".");
					ext = ext[ext.length - 1];
					CMS.trigger("file.add", {
						Title: title,
						DocPath: data.data.uploaded,
						Type: CMS.models.ContentType.DOCUMENT + "/" + ext,
						DocImgPath: "/cms/images/" + ext + ".png"
					});
				}
				CMS.trigger("loading", false);
			}, this)));
			
			// Submit form data and reset FILE field
			this.$(".file-upload-form").submit();
			$inp.val('');
		},
		
		userLogout: function(){
			CMS.trigger('user.logout');
		}
	});
	
	
	//AppError - simple application error
	views.AppError = KitchenView.extend({});
	
	
	//AppWarning - application warnings
	views.AppWarning = KitchenView.extend({});
	
		
	//AppInfo - application info popups
	views.AppInfo = KitchenView.extend({
		close: function(){
			this.$(".close").trigger("click");
		}
	});
	
	
	
	// Generic UI
	// ----------
	// Generic, extendable UI elements.

	//context-menu
	views.ContextMenuGeneric = views.KitchenView.extend({
		tagName: 'ul',

		events: {
			"click a[data-edit]":          "editItem",
			"click a[data-rtedit]":        "richEditItem",
			"click a[data-editinline]":    "editItemInline"
		},
		
		postRender_: function(){
			CMS.trigger("contextmenu.ready", this);
		},

		// Display this dropdown at position
		pos: function(x, y){
			var $el = this.$('.dropdown-toggle')
			  , pos = this.calcPos(x, y);
			$(this.el).css('position', 'absolute');
			$(this.el).css('left', pos[0]);
			$(this.el).css('top', pos[1]);
			this.toggle(true);
			//$el.dropdown();
			//$el.trigger('click');
		},
		
		// Calculates position to fit inside window bounds
		WD: 175, WPAD: 35,
		HG: 75, HPAD: 10,
		calcPos: function(x, y){
			var wd = $(window).width()
			  , hg = $(window).height()
			  , myhg = Math.max(this.HG, this.$("ul.dropdown-menu").height());
			if ((this.WD + x) > wd) {
				x = x - this.WD - this.WPAD;
			}
			if ((myhg + 10 + y) > hg) {
				y = y - myhg - this.HPAD;
			}
			return [x, y];
		},
		
		// Menu visibility
		visible: false,
		toggle: function(bool){
			this.$(".dropdown-menu").css('display', (bool) ? "block" : "none");
			this.visible = bool;
		},
		
		close: function(){
			this.toggle(false);
		},
		
		
		/// TextEdit
		
		// Features that are shown on menu's where text editing available.
		//XXX FIXME are these still used?
		
		editItem: function(e){
			CMS.trigger("textedit.modal", this.elem, {rich: false});
			this.close();
		},
		
		editItemInline: function(e){
			CMS.trigger("textedit.inline", this.elem);
			this.close();
		},
		
		richEditItem: function(e){
			CMS.trigger("textedit.modal", this.elem, {rich: true});
			this.close();
		}
		
	});



	//modal window
	views.ModalGeneric = views.KitchenView.extend({
		isModal_: true,
		
		events: {
			"click [data-dismiss=modal]":    "close",
			"keyup input[type=text]":        "submitForm"
		},

		// Make modal draggable (jQuery UI)
		makeDraggable: function(){
			this.$(".modal").draggable({
				handle: "[data-draghandle]"
			});
			this.$("[data-draghandle]").css("cursor", "move");
		},
		
		// Make modal resizable (jQuery UI)
		makeResizable: function(){
			return; //disabled for now
			this.$("[data-resizable]").resizable({
				handles: {
					e: '.resize-handle',
					s: '.resize-handle',
					se: '.resize-handle'
				}
			});
		},

		open: function(){
			$(this.el).show();
		},

		close: function(){
			$(this.el).hide();
			this.unwatchState();
			//XXX triger manually if you override 'close' method
			CMS.trigger("modal.removed", this);
		},

		submitForm: function(e){
			return; //XXX disabled for now
			if (e.keyCode === 13) {
				this.$("button.btn-info:not([data-bypass])").trigger('click');
			}
		},


		// Storing/restoring modal state
		stateWatch: function(m){
			m.set({
				PosX: this.$(".modal").css("left"),
				PosY: this.$(".modal").css("top")
			});
		},
		stateRestore: function(m){
			var m = m.toJSON();
			this.$(".modal").css("left", m.PosX);
			this.$(".modal").css("top", m.PosY);
		}
	});
	
	
	//modal-backdrop
	views.ModalBackdrop = views.KitchenView.extend({
		//...
	});
	
	
	//dropdown
	views.DropdownGeneric = views.KitchenView.extend({
		events: {
			"click .dropdown-toggle":   "toggleMenu"
		},

		visible_: false,
		
		toggle: function(bool){
			this.$(".dropdown-menu").css('display', (bool) ? "block" : "none");
			this.visible_ = bool;
		},
		
		// Render + keep dropdown visibility
		rerender: function(){
			this.render();
			this.toggle( this.visible_ );
		},
		
		// Dropdown emulation function
		toggleMenu: function(){
			var vis = this.visible_;
			CMS.trigger("dropdowns.hideall");
			CMS.trigger("textedit.inline.cancel.or.save");
			this.toggle( !vis );
		},


		// Turning watching on/off

		stateWatch: function(m){
			m.set({
				ScrollTop1: this.$(".sortableNested").scrollTop(),
				ScrollTop2: this.$(".hiden-pages").scrollTop()
			});
		},

		stateRestore: function(m){
			var m = m.toJSON();
			this.$(".sortableNested").scrollTop(m.ScrollTop1);
			this.$(".hiden-pages").scrollTop(m.ScrollTop2);
		}
		
	});

})();
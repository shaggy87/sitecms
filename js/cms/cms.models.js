/**
 * KITCHEN
 * Models, containers of logic.
 * 
 */
(function(){
	
	var models = CMS.models = CMS.models || {};

	// Base, extendable models
	var KitchenModel = models.BaseModel;
	var KitchenCollection = models.BaseCollection;

	
	//MainMenu - WebExpress XXX not needed - implement in view
	models.MainMenu = KitchenModel.extend({
		defaults: {
			expanded: false
		}
	});
	
	
	//Menu model
	models.Menu = KitchenModel.extend({
		name: "Menu"
	});
	
	
	//Logo model
	models.Logo = KitchenModel.extend({
		name: "Logo"
	});

	
	//Confirm box model
	models.ConfirmBox = KitchenModel.extend({
		defaults: {
			Action: function(){},
			Message: "",
			OnCancel: function(){}
		}
	});


	//ViewState
	models.ViewState = KitchenModel.extend({
		defaults: {
			DaemonInterval: 1500,       // General options

			PosX: null,                 // Modal options
			PosY: null,
			SelectedTab: null,
			ScrollTop: null,

			ScrollTop1: null,           // Dropdown options
			ScrollTop2: null
		}
	});


	//Modal
	models.Modal = KitchenModel.extend({
		defaults: {
			PosX: null,
			PosY: null,
			SelectedTab: null,
			ScrollTop: null,
			DaemonInterval: 1500
		}
	});


	//Dropdown
	models.Dropdown = KitchenModel.extend({
		defaults: {
			ScrollTop1: null,
			ScrollTop2: null,
			DaemonInterval: 500
		}
	});
	
	// link Content to page
	models.LinkContentToPage = KitchenModel.extend({
		defaults: {
			Pages: null,
			Content: null
		},
		
		initialize: function(){
			this.set({Pages: CMS.Pages});
		}
	});
	
	models.InlineRte = KitchenModel.extend({
		defaults: {
			Pos: [null, null],
			x: null,
			y: null,
			link: function(){},
			bold: function(){},
			italic: function(){},
			underline: function(){},
			unlink: function(){}
		},
		
		initialize: function(){
			this.updatePos();
		},
		
		updatePos: function(){
			var xpos, ypos;
			
			xpos = Math.round(this.get("x"));
			ypos = Math.round(this.get("y") - 55);
			this.set({
				Pos: [xpos, ypos]
			});
		},
		
		link: function(){
			var a = prompt("Insert link", "http://");
			if(a !== null)
				this.get("link")(a)
		},
		
		linkDoc: function(){
			model = this;
			CMS.trigger("document.choose.simple", "SelectDocument", model.get("link"));
		},
		
		bold: function(){
			this.get("bold")();
		},
		
		italic: function() {
			this.get("italic")();
		},
		
		underline: function() {
			this.get("underline")();
		},
		
		unlink: function() {
			this.get("unlink")();
		}
	});
	
})();
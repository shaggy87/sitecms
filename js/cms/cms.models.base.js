/**
 * KITCHEN
 * List of base, extendable models.
 * 
 */
(function(){
	
	var models = CMS.models = CMS.models || {};

	var localService = CMS.Service;


	// Core Models
	// -----------
	
	// BaseModel
	models.BaseModel = Backbone.Model.extend({

		// Service functions
		// @returns ServiceRequest

		load: function(){
			return localService.req(this.name+"/"+this.get("Id"), this);
		},

		store: function(){
			var id = this.get("Id");
			// new item or edit existing
			if (id === null || typeof id === "undefined") {
				return localService.req("new/"+this.name, this);
			} else {
				return localService.req("edit/"+this.name+"/"+id, this);
			}
		},
		
		remove: function(){
			var id = this.get("Id");
			if (id === null && typeof id === "undefined") return;
			return localService.req("delete/"+this.name+"/"+id, this);
		},

		exec: function(method, opts){
			var sstring = this.name+"/"+method;
			sstring += this.get("Id") ? "/"+this.get("Id") : "";
			return localService.req( sstring, this, opts );
		},
		
		saveProperties: function(){
			var m = this
			  , pold = m.get("Properties_")
			  , pnew = m.get("Properties")
			  , pchanged = []
			  , request = localService.req();
			  
			// calculate changed properties (from backup data)
			pchanged = _.reduce(pnew, function(memo, val, p){
				if ((typeof pold[p] === "undefined" && val !== "")
					|| (typeof pold[p] !== "undefined" && typeof val !== "undefined"
						&& pold[p] !== val)) {
					memo.push([p, val]);
				}
				return memo;
			}, []);
			
			_.each(pchanged, function(prop){
				request = request.open( m.exec("saveProperty", prop) );
			});
			
			m.backup("Properties");
			return request;
		},

		
		// Reverts to prevously saved version of model.
		previous_: {},
		saveAttributes: function(){
			this.previous_ = this.toJSON();
		},
		revertAttributes: function(){
			this.set( this.previous_ );
		},
		
		// Backup specific attribute (copy its value) to attribute with name
		// attrName_ (underscore added at end).
		// If value is Object - makes "deep" copy.
		backup: function(attrName){
			var val = this.get(attrName);
			val = typeof val === "object" ? _.extend({},val) : val;
			this.set(attrName+"_", val);
		}
		//...
	});
	
	
	
	// BaseCollection
	models.BaseCollection = Backbone.Collection.extend({
		name: "",
		cachable: true,
		
		// Get by "Id" parameter, or return null
		getById: function(id){
			var id = (typeof id === "string") ? parseInt(id, 10) : id
				, mch = this.filter(function(p){ return p.get('Id') === id; });
			return (typeof mch[0] === "undefined") ? null : mch[0];
		},
		
		// Remove from collection
		removeById: function(id){
			return this.remove( this.getById(id) );
		},

		// Return items sorted by "Id" parameter.
		sorted: function(asc){
			var asc = typeof asc === "undefined" ? false : asc
			  , i = asc ? 1 : -1;
			return this.sortBy(function(item){ return i * item.get("Id"); });
		},
		
		// Load all - with some caching
		//TODO rethink this usage form (Collections only)
		load: function(done, opts){
			var self = this
			  , done = (typeof done === "function") ? done : function(){}
			  , ops = (typeof opts === "undefined") ? {} : opts
			  , force = typeof ops.force !== "undefined" && ops.force === true;

			if (this.name === "") return;
			
			// cache this collection or fetch new
			if (this.length > 0 && this.cachable && !force) {
				return done(self);
			}
			
			var req = localService.req(this.name, this, ops);
			req.send(function(data){
				if (self.length > 0) {
					self.sset(data);
				} else {
					self.reset(data);
				}
				return done(self);
			});
		},


		// Return first found item, if any
		ffind: function(opts){
			return this.where(opts)[0];
		},
		
		// Check for item with passed attr-value pair, return boolean
		checkAttrExist: function(attr, value){
			return this.any(function(p){ return p.get( attr ) === value; });
		},
		
		// Filter items - get by unique attribute value
		getUniquesByAttr: function(attrName){
			var uniques = [];
			
			// filter only unique items
			return this.reduce(function(memo, item){
				var aval = item.get( attrName );
				if (_.indexOf(uniques, aval) < 0) {
					memo.push( item );
					uniques.push( aval );
				}
				return memo;
			}, []);
		},

		// Do smarter update of Collection
		sset: function(data){
			var cl = this;
			_.each(data, function(d){
				var o = typeof d.on === "function" ? d.toJSON() : d
				  , item = cl.getById(d.Id);
				if (item === null) {
					cl.add(d);
				} else {
					item.set(o);
				}
			});
			//TODO ...
			//cl.reset(data);
		}
		
	});
	
})();
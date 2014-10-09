/**
 * KITCHEN
 * Simple jQuery Backbone View plugin.
 * @requires CMS.core
 */

$(function(){

	
	/**
	 * Adds a Backbone View to specified HTML element.
	 *
	 * @method bview
 	 * @param {String}          viewName  View name
	 * @param {Object|Function} [model]   Model - instance or object hash
	 * @param {Function}        [done]    Callback (if 2nd param provided)
	 */
	jQuery.fn.bview = function(viewName, model, done){
		var target = this
		  , done = (typeof done !== "function") ? function(){} : done;

		var viewsLocation = CMS.views;

		var viewsPath = "views";

		// createView helper function
		var createView = function(View){
			var viewInstance
			  , options = {}
			  , element;

			if (typeof model.on === "function") {
				options.model = model;
			}

			//-create instance
			viewInstance = new View(options);
			viewInstance.template = viewName;
			viewInstance.name = viewName;

			//-render
			if (typeof model === "object") {
				if (typeof model.on === "function") {//1 - model
					element = viewInstance.render( done );
				} else {//2 - simple object
					element = viewInstance.render( model );
					done( viewInstance );
				}
			}
			if (typeof model === "function") {//3 - passed callback
				element = viewInstance.render( model );
			}

			//-add to target element
			$( element ).attr('data-CMSview', viewName);
			$( target ).append( element );
			CMS.trigger("view.add", viewInstance, viewName);
		};
		
		
		// Create view (first load it - if needed)
		if (typeof viewsLocation[ viewName ] === 'undefined') {
			//CMS.trigger("loading", true);
			CMS.load(viewsPath + "/" + viewName + ".js" +
				"?t=" + new Date().getTime(), function(){
				//CMS.trigger("loading", false);
				createView(viewsLocation[ viewName ]);
			});
		} else {
			createView(viewsLocation[ viewName ]);
		}
		
	};
	


	/**
	 * Tries to get exact position of element inside guest window.
	 * @return {Object} [:top, :left]   Position coordinates.
	 */
	/*jQuery.fn.CMSposition = function(yoff){
		return getoff($(this).get(0), yoff);
	};
	
	// solution #1
	var getoffset = function(el){
		var _x = 0;
		var _y = 0;
		while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
			_x += el.offsetLeft - el.scrollLeft;
			_y += el.offsetTop - el.scrollTop;
			el = el.offsetParent;
		}
		return { top: _y, left: _x };
	};
	
	// solution #2
	var getoff = function(elem, yoff){
		var yoff = typeof yoff === "undefined" ? 0 : yoff;
		if (typeof elem === "undefined") return;
		var obj = elem.getBoundingClientRect();
		return {
			top: Math.round(obj.top + yoff),
			left: Math.round(obj.left)
		};
	};*/

});
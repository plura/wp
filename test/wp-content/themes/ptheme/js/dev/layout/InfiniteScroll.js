plura.obj('ptheme.layout.InfiniteScroll', function (options) {

	"use strict";

	var defaults = {
			limit: 		0,
			selector: 	'infinite-scroll-status'
		}, 

		limit, offset, opts, ui_status,


		load = function () {

			var o = offset + limit, params = $.extend( {offset: o, limit: limit}, opts.params);

			$.get( options.process, params, eventLoadHandler );

			console.log( options.process + '?' + $.param(params));

			offset = o;

			enable_load( false );

			enable_load_ui();

		},


	   /**
		* enables window scroll event triggering
		* @param {boolean} [status=true] - the boolean value that indicates event handling activation or de-activation
		*/
		enable_load = function ( status ) {

			var s = status === undefined ? true : status;

			if (s) {

				$(window).on('scroll', eventScrollHandler);

			} else {

				$(window).off('scroll');

			}

		},


		enable_load_ui = function (status) {

			var s = status === undefined ? true : status;

			ui_status.appendTo( opts.target );

			if (s) {
				
				ui_status.fadeIn();

			} else {

				ui_status.fadeOut();

			}

		},


		nr_items = function () {

			return opts.target.children(':not(.' + opts.selector + ')').length;

		},


	   /**
		* adds the loaded data
		* @param {string} - the returned DOM structure data string
		*/
		eventLoadHandler = function ( data ) {

			opts.target.append( data );

			enable_load_ui( false );

			if (!opts.total || opts.total > nr_items() ) {

				enable_load();

			} else {

				ui_status.remove();

			}

		},


		eventScrollHandler = function () {

			if( $(window).scrollTop() === $(document).height() - $(window).height() ) {
        
				load();

		    }

		},


		init_ui = function () {

			return $('<div/>').append('<div class="fa fa-cog fa-spin"/>').addClass('col-md-12');

		},


		init = function () {

			opts		= $.extend(true, {}, defaults, options);

			offset		= 0;

			limit		= opts.limit || nr_items();

			if (!opts.total || opts.total > nr_items() ) {

				ui_status	= init_ui().appendTo( options.target ).addClass( opts.selector ).hide();

				enable_load();

			}

		};


	init();

});


//ECMAScript 5 getter/setters/event
(function () {
    
    "use strict";

	plura.events.EventDispatcher.prototype.apply( ptheme.layout.InfiniteScroll.prototype );

}());
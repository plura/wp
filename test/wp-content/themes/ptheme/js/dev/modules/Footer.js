plura.obj('ptheme.modules.Footer', function (options) {

	"use strict";

	var _this = this,


		resize = function ( data ) {

			if (!options.pwp.front_page) {

				position_footer( data );

			}

		},


		position_footer = function ( data ) {

			var body = $('body'), main = $('body>.container'), footer = $('body>footer');

			footer.removeClass('fixed');

			if (data.window.height - (main.outerHeight() + main.offset().top) > footer.outerHeight()) {

				footer.addClass('fixed');
			
			}

		};


	_this.resize = resize;


});
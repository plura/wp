plura.obj('ptheme.layout.ResizeManager', function (options) {

	"use strict";

	var data, objects, _this = this,


		add = function (obj) {

			var objs = obj instanceof Array ? obj : [obj];

			objects = (objects || []).concat( objs );

		},



		resize = function () {

			var i;

			refresh();

			if (objects) {

				for( i in objects ){

					if (objects.hasOwnProperty(i) && (typeof objects[i].resize === 'function')) {

						objects[i].resize( data );

					}

				}

			}

		},



		refresh = function () {

			data = {
				window: {
					height: $(window).height(),
					width:	$(window).width()
				}
			};

		},


		//triggers resize handler when window is resized and when is fully loaded
		eventWindowHandler = function (event) {

			if(event.type === 'load' && options.delay) {

				setTimeout( resize, options.delay );

			} else {

				resize();

			}

		},



		init = function () {

			$(window).resize(eventWindowHandler).load( eventWindowHandler );

			if (options.objects) {

				add( options.objects );

			}

		};


	_this.add		= add;
	_this.refresh	= refresh;


	init();


});
plura.obj('ptheme.utils', {

   /**
	* Adds or removes a map of css classes to specific objects
	* 
	*
	*
	*
	*
	*
	* @param {obj} - the classes map object
	* @param {boolean} [status=true] - status indicating if classes are added or removed
	* @param {string=} id - optional class prefix (used internally for recursive purposes)
	*/
	addClassMap: function (obj, add, id) {

		"use strict";

		var clss, prop,
			_add	= add === undefined ? true : add,
			_id		= id === undefined ? '' : id + '-';  /*prop, clss;*/

		for( prop in obj ) {

			if ( obj.hasOwnProperty( prop ) ) {

				clss = _id + prop;

				if ( (typeof obj[prop] === 'object') && !(obj[prop] instanceof jQuery) ) {					

					ptheme.utils.addClassMap( obj[prop], _add, clss );

				} else if (_add) {

					//console.log( clss + ":" + true);

					obj[prop].addClass( clss );

				} else {

					//console.log( clss + ":" + false);

					obj[prop].removeClass( clss );

				}

			}

		}

	},


	absPos: function (objects, cleanFirst) {

		objects.css({top: '', left: '', position: ''})

		.each( function() {

			$(this).data('pos', $(this).position());

		})

		.each( function() {

			var obj = $(this), pos = obj.data('pos');

			obj.css({left: pos.left, top: pos.top, position: 'absolute'});

		});


	}


});


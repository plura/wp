plura.obj('pTheme', function (options) {

	"use strict";

	var resizemanager,
	
		THEME_PROCESS	= options.process,
		THEME_LANG		= options.pwp.lang,


		get_module = function ( pwp ) {

			var module, target;

			if ( pwp.selected.type === 'page') {

				target = $('#content article');

				if ( pwp.selected.permalink === 'contacts' ) {

					module = new ptheme.modules.Contacts({
						lang: 		THEME_LANG, 
						process:	THEME_PROCESS,
						target: 	target
					});

				}

			}

			return module;

		},



		init_modules = function () {

			var a = [], pwp = options.pwp, module = get_module( pwp ),

				ui_quotes = $('#modal-quotes');

			if (module) {

				a.push( module );

			}

			a.push( new ptheme.modules.Footer({
					pwp:		pwp,
					process: 	THEME_PROCESS
			}) );

			if ( ui_quotes.length ) {

				a.push( new ptheme.modules.Quotes({
					lang:		THEME_LANG,
					process:	THEME_PROCESS,
					target:		ui_quotes
				}));

			}

			return a;

		},



		init = function () {

			var modules		= init_modules();

			resizemanager	= new ptheme.layout.ResizeManager({
				objects:	modules
			});

			$('#qtranslate-chooser').addClass('dropdown-menu');

		};


	init();

});



$(function(){ 

	var theme = new pTheme({
		process: 	PWP.template_dir + 'process/process.php',
		pwp: 		PWP
	});

});
plura.obj('pTheme', function (options) {

	"use strict";

	var resizemanager,

		init_modules = function () {

			var a = [], pwp = options.pwp, lang = pwp.lang, process = options.process,

				ui_quotes = $('#modal-quotes');

			a.push( new ptheme.modules.Footer({
					pwp:		pwp,
					process: 	process
			}) );

			if ( ui_quotes.length ) {

				a.push( new ptheme.modules.Quotes({
					lang:		lang,
					process:	process,
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
plura.obj('ptheme.modules.Quotes', function (options) {

	"use strict";

	var form_manager, form_trigger, _this = this,

		_ = ptheme.data.Lang/*, _self = novasotecma*/,


		//re-positions form_trigger just over the footer IF form_trigger position overlaps it
		position = function () {

			var win_h		= $(window).height(), 

				win_scroll	= $(window).scrollTop(),

				footer_y	= $('footer.main').offset().top,

				trigger_h	= form_trigger.outerHeight(),

				trigger_mrg	= parseInt( form_trigger.css('right'), 10); 

			
			form_trigger.css({bottom: ''});
		

			if ( (win_h + win_scroll) > footer_y ) {

				form_trigger.css({bottom: (win_h + win_scroll) - footer_y + trigger_mrg});

			}

		},


		resize = function () {

			position();

		},


		eventScrollHandler = function () {

			position();

		},


		eventFormManagerHandler = function (event, data) {

			switch (event.type) {

			case 'FORM_VALIDATION_ERROR':

				alert( data.alert );

				break;

			case 'FORM_PROCESS_ERROR':

				alert( data.alert.join('\n') );

				break;

			case 'FORM_PROCESS_SUCCESS':

				alert( data.alert.join('\n') );

				options.target.modal('hide');

				break;

			}

		},



		init = function () {

			var lang = options.lang;

			

			form_trigger	= $('button[data-target=#modal-quotes]');

			form_manager	= new plura.form.FormManager({				
				
				check:	[
					{id: 'user_email',	rules: [
						{alert: _.check.email.empty[ lang ]},
						{exp: plura.constants.RegExp.EMAIL, alert: _.check.email.invalid[ lang ]}
					]},
					{id: 'user_message',	alert: _.check.message[ lang ]},
					{id: 'user_name',		alert: _.check.name[ lang ]},
					{id: 'user_telephone',	alert: _.check.telephone[ lang ]}					
				],
				
				params:	{type: 'form-quotes', lang: lang/*, html: _self.globals.HTML_MAIL*/},

				path:	options.process,
				
				reset:	true,

				target: options.target.find('form')

			});

	
			form_manager.form.bind('FORM_VALIDATION_ERROR FORM_PROCESS_ERROR FORM_PROCESS_SUCCESS', eventFormManagerHandler);


			$(window).scroll( eventScrollHandler );


		};


	_this.resize = resize;


	init();

});
plura.obj('ptheme.modules.Contacts', function (options) {

	"use strict";

	var form_manager, ui_1st_column_last_group, ui_2nd_column_textarea, 

		_this = this, _ = ptheme.data.Lang,// _self = novasotecma,



		resize = function ( data ) {

			var h;
				
			if (data.window.width >= ptheme.constants.BreakPoints.WIDTH_SM) {

				h = ui_1st_column_last_group.position().top + ui_1st_column_last_group.outerHeight();

			}

			ui_2nd_column_textarea.outerHeight( h || '' );

		},



		eventFormManagerHandler = function (event, data) {

			switch (event.type) {

			case 'FORM_VALIDATION_ERROR':

				alert( data.alert );

				break;

			case 'FORM_PROCESS_ERROR':
			case 'FORM_PROCESS_SUCCESS':

				alert( data.alert.join('\n') );

				break;

			}

		},



		init = function () {

			var lang = options.lang;

			ui_1st_column_last_group	= options.target.find('form .col-xs-12.col-sm-5.col-md-6:nth-child(1) .form-group:last-child'); 

			ui_2nd_column_textarea		= options.target.find('textarea');

			form_manager				= new plura.form.FormManager({				
				
				check:	[
					{id: 'user_email',	rules: [
						{alert: _.check.email.empty[ lang ]},
						{exp: plura.constants.RegExp.EMAIL, alert: _.check.email.invalid[ lang ]}
					]},
					{id: 'user_message',	alert: _.check.message[ lang ]},
					{id: 'user_name',		alert: _.check.name[ lang ]},
					{id: 'user_telephone',	alert: _.check.telephone[ lang ]}					
				],
				
				params:	{type: 'form-contacts', lang: lang/*, html: _self.globals.HTML_MAIL*/},

				path:	options.process,
				
				reset:	true,

				target: $('.contacts-form')

			});

	
			form_manager.form.bind('FORM_PROCESS_ERROR FORM_PROCESS_SUCCESS FORM_VALIDATION_ERROR', eventFormManagerHandler);

		};


	_this.resize = resize;


	init();

});
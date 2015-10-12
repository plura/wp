var others = {

	fx:	{

		//adds an accordion effect to a list strcuture
		accordion: function (options) {

			var defaults	= {easing: 'slow'},

				opts		= $.extend({}, defaults, options),

				fn			= function () {

					$(this).parent().children('ul').toggle(opts.easing);

					return false;
				
				};


			opts.target.children('li').children('ul').hide();
			
			opts.target.children('li').children('a').click(fn);

		}		

	}

}
/*

  	//adds an accordion effect to a list strcuture
	$.fn.pFXAccordion	= function(options){
		
		var o = $.extend({}, $.fn.pFXAccordion.defaults, options);
		
 		return this.each(function() {
			$(this).children('ul').hide();
			$(this).children('a').click(function(){
				$(this).parent().children('ul').toggle(o.easing);
				return false;
			});
		});
	};	
	
	$.fn.pFXAccordion.defaults = {easing:'slow'};*/
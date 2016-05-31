/*
 * Plura P jQuery Plugin Collection for Wordpress
 * 
 * Copyright (c) 2015 Plura
 *
 * Date: 2016-05-24 12:30:50 (Tue, 02 Dez 2015)
 * Revision: 6246
 */
plura.obj(".wp.LightBox",function(a){var e={data:PWP.dir+"php/process.php?type=lightbox",group:"lightbox-gallery",size:"medium",options:{}},d,h=function(a){var c,b;for(c=0;c<a.length;c+=1)b=$("#"+a[c].id),b.attr("title")||(b.children("img").length&&b.children("img").attr("alt")?b.attr("title",b.children("img").attr("alt")):b.attr("title",b.text())),b.prop("href",a[c].url);if(!1!==d.group)d.target.colorbox(d.options);else for(c=0;c<a.length;c+=1)$("#"+a[c].id).colorbox(d.options)},k=function(a){a.preventDefault()};
(function(){var f=[],c,b,g=plura.wp.LightBox.i||0;d=$.extend(!0,{},e,a);a.target.length&&(a.target.each(function(a){c="lightbox_"+g+"_"+a;b="IMG"===$(this).prop("tagName")?$(this).parent():$(this);b.prop("id",c).click(k);!1!==d.group&&b.prop("rel",d.group);f[a]={id:c,url:b.prop("href")}}),$.getJSON(d.data,{data:f,size:d.size},h),plura.wp.LightBox.i=g+1)})()});(function(a){a.fn.pwpLightbox=plura.wp.LightBox})(jQuery);
plura.obj(".wp.NavSlide",function(a){var e=$.extend({},{speed:"slow"},a);$(this).find("li ul").addClass("jqhide");$(this).find("li").hover(function(){$(this).children("ul").slideDown(e.speed)},function(){$(this).children("ul").slideUp(e.speed)});$(this).find(".jqhide").css({left:"auto",display:"none"})});(function(a){a.fn.pwpNavSlide=plura.wp.NavSlide})(jQuery);
$(function(){$("li.current-cat").each(function(){for(var a=$(this).parent().parent();a.hasClass("cat-item");)a.addClass("current-cat-ancestor"),a=a.parent().parent()});$(".pwp_nav_ssf.slide").pwpNavSlide();var a=new plura.net.Client({classes:!0});PWP.lang&&$("body").addClass("lang-"+PWP.lang);a.data.ie&&7>a.data.version&&($("input[type=checkbox]").addClass("checkbox"),$("input[type=radio]").addClass("radio"),$("input[type=text]").addClass("text"),$("input[type=submit]").addClass("submit"))});

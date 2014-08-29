/*
 * Plura P jQuery Plugin Collection for Wordpress
 * 
 * Copyright (c) 2014 Plura
 *
 * Date: 2014-08-29 12:30:50 (Fri, 29 Aug 2014)
 * Revision: 6246
 */
var plura=plura||{};plura.wp=plura.wp||{};
plura.wp.LightBox=function(a){var b=[],d=this,e,c=$.extend(!0,{},{data:PWP.dir+"php/pwp.process.php?type=lightbox",group:"lightbox-gallery",size:"medium",options:{}},a);(function(){var a,f=plura.wp.LightBox.i||0;d.length&&(d.each(function(g){a="IMG"===$(this).prop("tagName")?$(this).parent():$(this);a.prop("id","lightbox_"+f+"_"+g);!1!==c.group&&a.prop("rel",c.group);b[g]='{"id":"'+a.prop("id")+'", "url": "'+a.prop("href")+'"}';a.click(function(a){a.preventDefault()})}),e={json:"["+b.join(",")+"]",
size:c.size},$.getJSON(c.data,e,function(a){$.each(a,function(a,b){$("#"+b.id).prop("href",b.url)});!1!==c.group?d.colorbox(c.options):$.each(a,function(a,b){$("#"+b.id).colorbox(c.options)})}),$.fn.pwpLightbox.i=f+1)})()};(function(a){a.fn.pwpLightbox=plura.wp.LightBox})(jQuery);plura=plura||{};plura.wp=plura.wp||{};
plura.wp.NavSlide=function(a){var b=$.extend({},{speed:"slow"},a);$(this).find("li ul").addClass("jqhide");$(this).find("li").hover(function(){$(this).children("ul").slideDown(b.speed)},function(){$(this).children("ul").slideUp(b.speed)});$(this).find(".jqhide").css({left:"auto",display:"none"})};(function(a){a.fn.pwpNavSlide=plura.wp.NavSlide})(jQuery);
$(function(){$("li.current-cat").each(function(){for(var a=$(this).parent().parent();a.hasClass("cat-item");)a.addClass("current-cat-ancestor"),a=a.parent().parent()});$(".pwp_nav_ssf.slide").pwpNavSlide();var a=new plura.net.Client({classes:!0});PWP.lang&&$("body").addClass("lang-"+PWP.lang);a.data.ie&&7>a.data.version&&($("input[type=checkbox]").addClass("checkbox"),$("input[type=radio]").addClass("radio"),$("input[type=text]").addClass("text"),$("input[type=submit]").addClass("submit"))});
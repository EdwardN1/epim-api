jQuery('.search-toggle').on('click',function(){
	jQuery(this).toggleClass('active');
	jQuery('.search-box').toggleClass('open');
});


jQuery(function(jQuery) {
  jQuery('.product-list-name').matchHeight();
  jQuery('.download-filter').matchHeight();

});


  jQuery(".hamburger").click(function(){
    jQuery('#hamburger').toggleClass("is-active");
    jQuery('body').toggleClass("menu-open");
  });


  jQuery(".has-dropdown").hover(function(){
    jQuery('.header').toggleClass("drop");
  });



jQuery('a.search-click').click(function(e)
{
    // Special stuff to do when this link is clicked...

    // Cancel the default action
    e.preventDefault();
});

// Gallery Slider
jQuery('.gallery-slides').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  fade: false,
  autoplay: true,
  asNavFor: '.slider-thumbs',
});

jQuery('.slider-thumbs').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  asNavFor: '.gallery-slides',
  dots: false,
  centerMode: false,
  focusOnSelect: true,
  arrows: true,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        dots: true
      }
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});


jQuery('.filter-all').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-tag-all').show();
  jQuery('.filter-products').removeClass('active');
  jQuery('.filter-sectors').removeClass('active');
  jQuery('.filter-troubleshooting').removeClass('active');
});

jQuery('.filter-products').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-tag-products').show();
  jQuery('.filter-tag-sectors').hide();
  jQuery('.filter-tag-troubleshooting').hide();
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-sectors').removeClass('active');
  jQuery('.filter-troubleshooting').removeClass('active');
});

jQuery('.filter-sectors').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-products').removeClass('active');
  jQuery('.filter-troubleshooting').removeClass('active');
  jQuery('.filter-tag-products').hide();
  jQuery('.filter-tag-sectors').show();
  jQuery('.filter-tag-troubleshooting').hide();
});

jQuery('.filter-troubleshooting').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-products').removeClass('active');
  jQuery('.filter-sectors').removeClass('active');
  jQuery('.filter-tag-products').hide();
  jQuery('.filter-tag-sectors').hide();
  jQuery('.filter-tag-troubleshooting').show();
});





jQuery('.filter-all').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-tag-all').removeClass('filter-fade');
  jQuery('.filter-metric').removeClass('active');
  jQuery('.filter-iso').removeClass('active');
  jQuery('.filter-ansi').removeClass('active');
  jQuery('.filter-hygenic').removeClass('active');
  jQuery('.filter-press').removeClass('active');
  jQuery('.filter-valves').removeClass('active');
  jQuery('.filter-extruded').removeClass('active');

});




jQuery('.filter-metric').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-iso').removeClass('active');
  jQuery('.filter-ansi').removeClass('active');
  jQuery('.filter-hygenic').removeClass('active');
  jQuery('.filter-press').removeClass('active');
  jQuery('.filter-valves').removeClass('active');
  jQuery('.filter-extruded').removeClass('active');
  jQuery('.filter-tag-metric').removeClass('filter-fade');
  jQuery('.filter-tag-iso').addClass('filter-fade');
  jQuery('.filter-tag-ansi').addClass('filter-fade');
  jQuery('.filter-tag-hygenic').addClass('filter-fade');
  jQuery('.filter-tag-press').addClass('filter-fade');
  jQuery('.filter-tag-valves').addClass('filter-fade');
  jQuery('.filter-tag-extruded').addClass('filter-fade');
});

jQuery('.filter-iso').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-metric').removeClass('active');
  jQuery('.filter-ansi').removeClass('active');
  jQuery('.filter-hygenic').removeClass('active');
  jQuery('.filter-press').removeClass('active');
  jQuery('.filter-valves').removeClass('active');
  jQuery('.filter-extruded').removeClass('active');
  jQuery('.filter-tag-metric').addClass('filter-fade');
  jQuery('.filter-tag-iso').removeClass('filter-fade');
  jQuery('.filter-tag-ansi').addClass('filter-fade');
  jQuery('.filter-tag-hygenic').addClass('filter-fade');
  jQuery('.filter-tag-press').addClass('filter-fade');
  jQuery('.filter-tag-valves').addClass('filter-fade');
  jQuery('.filter-tag-extruded').addClass('filter-fade');
});


jQuery('.filter-ansi').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-metric').removeClass('active');
  jQuery('.filter-iso').removeClass('active');
  jQuery('.filter-hygenic').removeClass('active');
  jQuery('.filter-press').removeClass('active');
  jQuery('.filter-valves').removeClass('active');
  jQuery('.filter-extruded').removeClass('active');
  jQuery('.filter-tag-metric').addClass('filter-fade');
  jQuery('.filter-tag-iso').addClass('filter-fade');
  jQuery('.filter-tag-ansi').removeClass('filter-fade');
  jQuery('.filter-tag-hygenic').addClass('filter-fade');
  jQuery('.filter-tag-press').addClass('filter-fade');
  jQuery('.filter-tag-valves').addClass('filter-fade');
  jQuery('.filter-tag-extruded').addClass('filter-fade');
});

jQuery('.filter-hygenic').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-metric').removeClass('active');
  jQuery('.filter-iso').removeClass('active');
  jQuery('.filter-ansi').removeClass('active');
  jQuery('.filter-press').removeClass('active');
  jQuery('.filter-valves').removeClass('active');
  jQuery('.filter-extruded').removeClass('active');
  jQuery('.filter-tag-metric').addClass('filter-fade');
  jQuery('.filter-tag-iso').addClass('filter-fade');
  jQuery('.filter-tag-ansi').addClass('filter-fade');
  jQuery('.filter-tag-hygenic').removeClass('filter-fade');
  jQuery('.filter-tag-press').addClass('filter-fade');
  jQuery('.filter-tag-valves').addClass('filter-fade');
  jQuery('.filter-tag-extruded').addClass('filter-fade');
});


jQuery('.filter-press').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-metric').removeClass('active');
  jQuery('.filter-iso').removeClass('active');
  jQuery('.filter-ansi').removeClass('active');
  jQuery('.filter-hygenic').removeClass('active');
  jQuery('.filter-valves').removeClass('active');
  jQuery('.filter-extruded').removeClass('active');
  jQuery('.filter-tag-metric').addClass('filter-fade');
  jQuery('.filter-tag-iso').addClass('filter-fade');
  jQuery('.filter-tag-ansi').addClass('filter-fade');
  jQuery('.filter-tag-hygenic').addClass('filter-fade');
  jQuery('.filter-tag-press').removeClass('filter-fade');
  jQuery('.filter-tag-valves').addClass('filter-fade');
  jQuery('.filter-tag-extruded').addClass('filter-fade');
});

jQuery('.filter-valves').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-metric').removeClass('active');
  jQuery('.filter-iso').removeClass('active');
  jQuery('.filter-ansi').removeClass('active');
  jQuery('.filter-hygenic').removeClass('active');
  jQuery('.filter-press').removeClass('active');
  jQuery('.filter-extruded').removeClass('active');
  jQuery('.filter-tag-metric').addClass('filter-fade');
  jQuery('.filter-tag-iso').addClass('filter-fade');
  jQuery('.filter-tag-ansi').addClass('filter-fade');
  jQuery('.filter-tag-hygenic').addClass('filter-fade');
  jQuery('.filter-tag-press').addClass('filter-fade');
  jQuery('.filter-tag-valves').removeClass('filter-fade');
  jQuery('.filter-tag-extruded').addClass('filter-fade');
});


jQuery('.filter-extruded').click(function(){
  jQuery(this).addClass('active');
  jQuery('.filter-all').removeClass('active');
  jQuery('.filter-metric').removeClass('active');
  jQuery('.filter-iso').removeClass('active');
  jQuery('.filter-ansi').removeClass('active');
  jQuery('.filter-hygenic').removeClass('active');
  jQuery('.filter-press').removeClass('active');
  jQuery('.filter-valves').removeClass('active');
  jQuery('.filter-tag-metric').addClass('filter-fade');
  jQuery('.filter-tag-iso').addClass('filter-fade');
  jQuery('.filter-tag-ansi').addClass('filter-fade');
  jQuery('.filter-tag-hygenic').addClass('filter-fade');
  jQuery('.filter-tag-press').addClass('filter-fade');
  jQuery('.filter-tag-valves').addClass('filter-fade');
  jQuery('.filter-tag-extruded').removeClass('filter-fade');
});





// Menu Items Slidetoggle
jQuery('.sub-menu').click(function() {
  jQuery(this).toggleClass('submenu-open').parent('li').siblings('li').children('h4.submenu-open').removeClass('submenu-open');
  jQuery(this).parent().toggleClass('submenu-open').children('ul').slideToggle(500).end().siblings('.submenu-open').removeClass('submenu-open').children('ul').slideUp(500);
  jQuery(this).children('i').toggleClass('open');
});







jQuery(window).on('resize load', function (){

jQuerydocument = jQuery(document);


var jQuerydocument = jQuery(document),
    jQueryelement = jQuery('.header'),
    className = 'scrolled';

jQuerydocument.scroll(function() {
  if (jQuerydocument.scrollTop() >= 50) {
    if (jQuerydocument.width() >= 992) {
    jQueryelement.addClass(className);
  }
  } else {
    jQueryelement.removeClass(className);
  }
});


    var prodboxWidth = jQuery('.product-box').width() + 60;
    jQuery('.product-box').css( "height", prodboxWidth );

    var teamWidth = jQuery('.team-member-image').width();
    jQuery('.team-member-image').css( "height", teamWidth * 1.3 );

    var newsboxWidth = jQuery('.news-big').width() + 60;
    jQuery('.news-big').css( "height", newsboxWidth );
    jQuery('.news-small').css( "height", newsboxWidth / 2);

}).resize();


var jQueryitem = jQuery('#carousel .item');
var jQuerywHeight = jQuery(window).height() - 200;
jQueryitem.eq(0).addClass('active');
jQueryitem.height(jQuerywHeight);
jQueryitem.addClass('full-screen');

jQuery('#carousel img').each(function() {
  var jQuerysrc = jQuery(this).attr('src');
  var jQuerycolor = jQuery(this).attr('data-color');
  jQuery(this).parent().css({
    'background-image' : 'url(' + jQuerysrc + ')',
    'background-color' : jQuerycolor
  });
  jQuery(this).remove();
});

jQuery(window).on('resize', function (){
  jQuerywHeight = jQuery(window).height() - 200;
  jQueryitem.height(jQuerywHeight);
});

jQuery('.carousel').carousel({
  interval: 6000,
  pause: "false"
});



/* ADD CLASS TO HEADER WHEN THE PAGE HAS SROLLED MORE THAN THE HEIGHT OF THE TOP NAV */




/*!
 * Retina.js v1.3.0
 *
 * Copyright 2014 Imulus, LLC
 * Released under the MIT license
 *
 * Retina.js is an open source script that makes it easy to serve
 * high-resolution images to devices with retina displays.
 */
!function(){function a(){}function b(a){return f.retinaImageSuffix+a}function c(a,c){if(this.path=a||"","undefined"!=typeof c&&null!==c)this.at_2x_path=c,this.perform_check=!1;else{if(void 0!==document.createElement){var d=document.createElement("a");d.href=this.path,d.pathname=d.pathname.replace(g,b),this.at_2x_path=d.href}else{var e=this.path.split("?");e[0]=e[0].replace(g,b),this.at_2x_path=e.join("?")}this.perform_check=!0}}function d(a){this.el=a,this.path=new c(this.el.getAttribute("src"),this.el.getAttribute("data-at2x"));var b=this;this.path.check_2x_variant(function(a){a&&b.swap()})}var e="undefined"==typeof exports?window:exports,f={retinaImageSuffix:"@2x",check_mime_type:!0,force_original_dimensions:!0};e.Retina=a,a.configure=function(a){null===a&&(a={});for(var b in a)a.hasOwnProperty(b)&&(f[b]=a[b])},a.init=function(a){null===a&&(a=e);var b=a.onload||function(){};a.onload=function(){var a,c,e=document.getElementsByTagName("img"),f=[];for(a=0;a<e.length;a+=1)c=e[a],c.getAttributeNode("data-no-retina")||f.push(new d(c));b()}},a.isRetina=function(){var a="(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)";return e.devicePixelRatio>1?!0:e.matchMedia&&e.matchMedia(a).matches?!0:!1};var g=/\.\w+jQuery/;e.RetinaImagePath=c,c.confirmed_paths=[],c.prototype.is_external=function(){return!(!this.path.match(/^https?\:/i)||this.path.match("//"+document.domain))},c.prototype.check_2x_variant=function(a){var b,d=this;return this.is_external()?a(!1):this.perform_check||"undefined"==typeof this.at_2x_path||null===this.at_2x_path?this.at_2x_path in c.confirmed_paths?a(!0):(b=new XMLHttpRequest,b.open("HEAD",this.at_2x_path),b.onreadystatechange=function(){if(4!==b.readyState)return a(!1);if(b.status>=200&&b.status<=399){if(f.check_mime_type){var e=b.getResponseHeader("Content-Type");if(null===e||!e.match(/^image/i))return a(!1)}return c.confirmed_paths.push(d.at_2x_path),a(!0)}return a(!1)},b.send(),void 0):a(!0)},e.RetinaImage=d,d.prototype.swap=function(a){function b(){c.el.complete?(f.force_original_dimensions&&(c.el.setAttribute("width",c.el.offsetWidth),c.el.setAttribute("height",c.el.offsetHeight)),c.el.setAttribute("src",a)):setTimeout(b,5)}"undefined"==typeof a&&(a=this.path.at_2x_path);var c=this;b()},a.isRetina()&&a.init(e)}();

/*
* jquery-match-height 0.7.2 by @liabru
* http://brm.io/jquery-match-height/
* License MIT
*/
!function(t){"use strict";"function"==typeof define&&define.amd?define(["jquery"],t):"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):t(jQuery)}(function(t){var e=-1,o=-1,n=function(t){return parseFloat(t)||0},a=function(e){var o=1,a=t(e),i=null,r=[];return a.each(function(){var e=t(this),a=e.offset().top-n(e.css("margin-top")),s=r.length>0?r[r.length-1]:null;null===s?r.push(e):Math.floor(Math.abs(i-a))<=o?r[r.length-1]=s.add(e):r.push(e),i=a}),r},i=function(e){var o={
byRow:!0,property:"height",target:null,remove:!1};return"object"==typeof e?t.extend(o,e):("boolean"==typeof e?o.byRow=e:"remove"===e&&(o.remove=!0),o)},r=t.fn.matchHeight=function(e){var o=i(e);if(o.remove){var n=this;return this.css(o.property,""),t.each(r._groups,function(t,e){e.elements=e.elements.not(n)}),this}return this.length<=1&&!o.target?this:(r._groups.push({elements:this,options:o}),r._apply(this,o),this)};r.version="0.7.2",r._groups=[],r._throttle=80,r._maintainScroll=!1,r._beforeUpdate=null,
r._afterUpdate=null,r._rows=a,r._parse=n,r._parseOptions=i,r._apply=function(e,o){var s=i(o),h=t(e),l=[h],c=t(window).scrollTop(),p=t("html").outerHeight(!0),u=h.parents().filter(":hidden");return u.each(function(){var e=t(this);e.data("style-cache",e.attr("style"))}),u.css("display","block"),s.byRow&&!s.target&&(h.each(function(){var e=t(this),o=e.css("display");"inline-block"!==o&&"flex"!==o&&"inline-flex"!==o&&(o="block"),e.data("style-cache",e.attr("style")),e.css({display:o,"padding-top":"0",
"padding-bottom":"0","margin-top":"0","margin-bottom":"0","border-top-width":"0","border-bottom-width":"0",height:"100px",overflow:"hidden"})}),l=a(h),h.each(function(){var e=t(this);e.attr("style",e.data("style-cache")||"")})),t.each(l,function(e,o){var a=t(o),i=0;if(s.target)i=s.target.outerHeight(!1);else{if(s.byRow&&a.length<=1)return void a.css(s.property,"");a.each(function(){var e=t(this),o=e.attr("style"),n=e.css("display");"inline-block"!==n&&"flex"!==n&&"inline-flex"!==n&&(n="block");var a={
display:n};a[s.property]="",e.css(a),e.outerHeight(!1)>i&&(i=e.outerHeight(!1)),o?e.attr("style",o):e.css("display","")})}a.each(function(){var e=t(this),o=0;s.target&&e.is(s.target)||("border-box"!==e.css("box-sizing")&&(o+=n(e.css("border-top-width"))+n(e.css("border-bottom-width")),o+=n(e.css("padding-top"))+n(e.css("padding-bottom"))),e.css(s.property,i-o+"px"))})}),u.each(function(){var e=t(this);e.attr("style",e.data("style-cache")||null)}),r._maintainScroll&&t(window).scrollTop(c/p*t("html").outerHeight(!0)),
this},r._applyDataApi=function(){var e={};t("[data-match-height], [data-mh]").each(function(){var o=t(this),n=o.attr("data-mh")||o.attr("data-match-height");n in e?e[n]=e[n].add(o):e[n]=o}),t.each(e,function(){this.matchHeight(!0)})};var s=function(e){r._beforeUpdate&&r._beforeUpdate(e,r._groups),t.each(r._groups,function(){r._apply(this.elements,this.options)}),r._afterUpdate&&r._afterUpdate(e,r._groups)};r._update=function(n,a){if(a&&"resize"===a.type){var i=t(window).width();if(i===e)return;e=i;
}n?o===-1&&(o=setTimeout(function(){s(a),o=-1},r._throttle)):s(a)},t(r._applyDataApi);var h=t.fn.on?"on":"bind";t(window)[h]("load",function(t){r._update(!1,t)}),t(window)[h]("resize orientationchange",function(t){r._update(!0,t)})});

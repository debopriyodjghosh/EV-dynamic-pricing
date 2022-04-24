/*
 Plugin: jQuery Parallax
 Version 1.1.3
 Author: Ian Lunn
 Twitter: @IanLunn
 Author URL: http://www.ianlunn.co.uk/
 Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

 Dual licensed under the MIT and GPL licenses:
 http://www.opensource.org/licenses/mit-license.php
 http://www.gnu.org/licenses/gpl.html
 */
!function(n){"use strict";var t=n(window),i=t.height();t.resize(function(){i=t.height()}),n.fn.parallax=function(o,e,r){function u(){var r=t.scrollTop();l.each(function(){var t=n(this),u=t.offset().top,f=c(t);u+f<r||u>r+i||l.css("backgroundPosition",o+" "+Math.round((h-r)*e)+"px")})}var c,h,l=n(this);l.each(function(){h=l.offset().top}),c=r?function(n){return n.outerHeight(!0)}:function(n){return n.height()},(arguments.length<1||null===o)&&(o="50%"),(arguments.length<2||null===e)&&(e=.1),(arguments.length<3||null===r)&&(r=!0),t.bind("scroll",u).resize(u),u()}}(jQuery);
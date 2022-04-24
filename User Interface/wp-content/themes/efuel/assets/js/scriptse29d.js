"use strict";


jQuery(document).on('ready', function() { 

	initSwiper();
	initEvents();
	initStyles();
	initMap();
	initCollapseMenu();	
	checkCountUp();	
	initScrollReveal();
	initCountDown();

	if (!/Mobi/.test(navigator.userAgent) && jQuery(window).width() > 768) jQuery('.matchHeight').matchHeight();
});

jQuery(window).on('scroll', function (event) {

	checkNavbar();
	checkGoTop();
}).scroll();

jQuery(window).on('load', function(){

	initMasonry();
	initParallax();
});

/* Navbar menu initialization */
function initCollapseMenu() {

	var navbar = jQuery('#navbar'),
		navbar_toggle = jQuery('.navbar-toggle'),
		navbar_wrapper = jQuery("#nav-wrapper");

    navbar_wrapper.on('click', '.navbar-toggle', function (e) {

        navbar_toggle.toggleClass('collapsed');
        navbar.toggleClass('collapse');
        navbar_wrapper.toggleClass('mob-visible');
    });

	// Anchor mobile menu
	navbar.on('click', '.menu-item-type-custom > a', function(e) {

		if ( typeof jQuery(this).attr('href') !== 'undefined' && jQuery(this).attr('href') !== '#' && jQuery(this).attr('href').charAt(0) === '#' )  {

	        navbar_toggle.addClass('collapsed');
	        navbar.addClass('collapse');
	        navbar_wrapper.removeClass('mob-visible');
    	}  	    
    });

    navbar.on('click', '.menu-item-has-children > a', function(e) {

    	var el = jQuery(this);

    	if (!el.closest('#navbar').hasClass('collapse')) {

    		if ((el.attr('href') === undefined || el.attr('href') === '#') || e.target.tagName == 'A') {

		    	el.next().toggleClass('show');
		    	el.next().children().toggleClass('show');
		    	el.parent().toggleClass('show');

		    	return false;
		    }
	    }
    });

    var lastWidth;
    jQuery(window).on("resize", function () {

    	checkNavbar();

    	var winWidth = jQuery(window).width(),
    		winHeight = jQuery(window).height();

       	lastWidth = winWidth;
    });	
}

/* Navbar attributes depends on resolution and scroll status */
function checkNavbar() {

	var navbar = jQuery('#navbar'),
		scroll = jQuery(window).scrollTop(),
    	navBar = jQuery('nav.navbar:not(.no-dark)'),
    	topBar = jQuery('.ltx-topbar-block'),
    	navbar_toggle = jQuery('.navbar-toggle'),
    	navbar_wrapper = jQuery("#nav-wrapper"),
	    slideDiv = jQuery('.slider-full'),
	    winWidth = jQuery(window).width(),
    	winHeight = jQuery(window).height(),
		navbar_mobile_width = navbar.data('mobile-screen-width');

   	if ( winWidth < navbar_mobile_width ) {

		navbar.addClass('navbar-mobile').removeClass('navbar-desktop');
	}
		else {

		navbar.addClass('navbar-desktop').removeClass('navbar-mobile');
	}

	navbar_wrapper.addClass('inited');

	if ( topBar.length ) {

		navBar.data('offset-top', topBar.height());
	}

    if (winWidth > navbar_mobile_width && navbar_toggle.is(':hidden')) {
        navbar.addClass('collapse');
        navbar_toggle.addClass('collapsed');
        navbar_wrapper.removeClass('mob-visible');
    }

    if (scroll > 1) navBar.addClass('dark'); else navBar.removeClass('dark');
}

/* Check GoTop Visibility*/
function checkGoTop() {

	var gotop = jQuery('.ltx-go-top'),
		scrollBottom = jQuery(document).height() - jQuery(window).height() - jQuery(window).scrollTop();

	if ( gotop.length ) {

		if ( jQuery(window).scrollTop() > 100 ) {

			gotop.addClass('show');
		}
			else {

			gotop.removeClass('show');
    	}

    	if ( scrollBottom < 50 ) {

    		gotop.addClass('scroll-bottom');
    	}
    		else {

    		gotop.removeClass('scroll-bottom');
   		}
	}	
}

/* All keyboard and mouse events */
function initEvents() {

	jQuery('.swipebox').swipebox();

	setTimeout(function() { if ( typeof Pace !== 'undefined' && jQuery('body').hasClass('paceloader-enabled') ) { Pace.stop(); }  }, 3000);	

	// WooCommerce grid-list toggle
	jQuery('.gridlist-toggle').on('click', 'a', function() {

		jQuery('.matchHeight').matchHeight();
	});

	jQuery('.menu-types').on('click', 'a', function() {

		var el = jQuery(this);

		el.addClass('active').siblings('.active').removeClass('active');
		el.parent().find('.type-value').val(el.data('value'));

		return false;
	});

	/* Scrolling to navbar from "go top" button in footer */
    jQuery('footer').on('click', '.ltx-go-top', function() {

	    jQuery('html, body').animate({ scrollTop: 0 }, 1200);
	});

    jQuery('.alert').on('click', '.close', function() {

	    jQuery(this).parent().fadeOut();
	    return false;
	});	

	jQuery(".topbar-icons.mobile, .topbar-icons.icons-hidden")
		.mouseover(function() {

			jQuery('.topbar-icons.icons-hidden').addClass('show');
			jQuery('#navbar').addClass('muted');
		})
		.mouseout(function() {
			jQuery('.topbar-icons.icons-hidden').removeClass('show');
			jQuery('#navbar').removeClass('muted');
	});

	// TopBar Search
    var searchHandler = function(event){

        if (jQuery(event.target).is(".top-search, .top-search *")) return;
        jQuery(document).off("click", searchHandler);
        jQuery('.top-search').removeClass('show-field');
        jQuery('.ltx-topbar-block').removeClass('muted');
    }

    jQuery('#top-search-ico-close').on('click', function (e) {

		jQuery(this).parent().toggleClass('show-field');
		jQuery('.ltx-topbar-block').toggleClass('muted');    	
    });

	jQuery('#top-search-ico').on('click', function (e) {

		e.preventDefault();
		jQuery(this).parent().toggleClass('show-field');
		jQuery('.ltx-topbar-block').toggleClass('muted');

        if (jQuery(this).parent().hasClass('show-field')) {

        	jQuery(document).on("click", searchHandler);
        }
        	else {

        	jQuery(document).off("click", searchHandler);
        }
	});

	jQuery('.top-search input').keypress(function (e) {
		if (e.which == 13) {
			window.location = '/?s=' + jQuery('.top-search input').val();
			return false;
		}
	});

	jQuery('.woocommerce').on('click', 'div.quantity > span', function(e) {

		var f = jQuery(this).siblings('input');
		if (jQuery(this).hasClass('more')) {
			f.val(Math.max(0, parseInt(f.val()))+1);
		} else {
			f.val(Math.max(1, Math.max(0, parseInt(f.val()))-1));
		}
		e.preventDefault();

		jQuery(this).siblings('input').change();

		return false;
	});

	initRentalFilter();
}

function initCountDown() {

	var countDownEl = jQuery('.ltx-countdown');

	if (jQuery(countDownEl).length) {

			jQuery(countDownEl).each(function(i, el) {

			jQuery(el).countdown(jQuery(el).data('date'), function(event) {
				console.log(event.strftime('' + jQuery(countDownEl).data('template')));
				//console.log(event.strftime('' + '<span>%S <span>seconds</span></span>'));

				jQuery(this).html(event.strftime('' + jQuery(countDownEl).data('template')));
			});		
		});
	}
}

function ltxUrlDecode(str) {

   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

/* Parallax initialization */
function initParallax() {

	// Only for desktop
	if (/Mobi/.test(navigator.userAgent)) return false;

	jQuery('.ltx-parallax').parallax("50%", 0.4);	

	if ( jQuery('.ltx-parallax-slider').length ) {

		var scene = jQuery('.ltx-parallax-slider').get(0);
		var parallaxInstance = new Parallax(scene, {

			hoverOnly : true,
			selector : '.ltx-layer',
			limitY : 0,
		});
	}

	jQuery(".ltx-scroll-parallax").each(function(i, el) {

		jQuery(el).paroller({ factor: jQuery(el).data('factor'), type: 'foreground', direction: jQuery(el).data('direction') });
	});
}

/* Adding custom classes to element */
function initStyles() {

	jQuery('form:not(.checkout) select:not(#rating), aside select').wrap('<div class="select-wrap"></div>');
	jQuery('.wpcf7-checkbox').parent().addClass('margin-none');

	jQuery('input[type="submit"], button[type="submit"]').addClass('btn btn-default btn-xs');
	jQuery('button.single_add_to_cart_button, .add_to_cart_button').addClass('btn-xs');
	jQuery('#send_comment').removeClass('btn-xs');
	jQuery('#searchsubmit').removeClass('btn');

	jQuery('.form-btn-shadow .btn,.form-btn-shadow input[type="submit"]').addClass('btn-shadow');
	jQuery('.form-btn-wide .btn,.form-btn-wide input[type="submit"]').addClass('btn-wide');

	jQuery('.woocommerce .button').addClass('btn').removeClass('button');
	jQuery('.woocommerce-message .btn, .woocommerce-info .btn').addClass('btn-xs');
	jQuery('.woocommerce .price_slider_amount .button').addClass('btn btn-black btn-xs color-text-white color-hover-second').removeClass('button');

	jQuery('.widget_product_search button').removeClass('btn btn-default btn-xs');
	jQuery('.input-group-append .btn').removeClass('btn-default btn-xs');

	jQuery('.ltx-hover-logos img').each(function(i, el) { jQuery(el).clone().addClass('ltx-img-hover').insertAfter(el); });
	
	jQuery(".container input[type=\"submit\"], .container input[type=\"button\"]").not('.btn-xs').wrap('<span class="ltx-btn-wrap"></span');
	jQuery(".container .wpcf7-submit").removeClass('btn-xs').wrap('<span class="ltx-btn-wrap"></span');

	jQuery('.bg-color-second .heading.theme-icon-second').removeClass('theme-icon-second').addClass('theme-icon-main');

	jQuery('.blog-post .nav-links > a').wrapInner('<span></span>');
	jQuery('.blog-post .nav-links > a[rel="next"]').wrap('<span class="next"></span>');
	jQuery('.blog-post .nav-links > a[rel="prev"]').wrap('<span class="prev"></span>');

	jQuery('section.bg-overlay-black, .wpb_row.bg-overlay-black').prepend('<div class="ltx-overlay-black"></div>');
	jQuery('section.bg-overlay-dark, .wpb_row.bg-overlay-dark').prepend('<div class="ltx-overlay-dark"></div>');
	jQuery('section.bg-overlay-xblack, .wpb_row.bg-overlay-xblack').prepend('<div class="ltx-overlay-xblack"></div>');
	jQuery('section.bg-overlay-gradient, .wpb_row.bg-overlay-gradient').prepend('<div class="ltx-overlay-gradient"></div>');
	jQuery('section.white-space-top, .wpb_row.white-space-top').prepend('<div class="ltx-white-space-top"></div>');

	// Settings copyrights overlay for non-default heights
	var footer = jQuery('#ltx-widgets-footer + .copyright-block'),
		widgets_footer = jQuery('#ltx-widgets-footer'),
		footerHeight = footer.outerHeight() + 1;

	widgets_footer.css('padding-bottom', 55 + footerHeight + 'px');
	footer.css('margin-top', '-' + footerHeight + 'px');


	// Cart quanity change
	jQuery('.woocommerce div.quantity,.woocommerce-page div.quantity').append('<span class="more"></span><span class="less"></span>');
	jQuery(document).off('updated_wc_div').on('updated_wc_div', function () {

		jQuery('.woocommerce div.quantity,.woocommerce-page div.quantity').append('<span class="more"></span><span class="less"></span>');
		initStyles();
	});

	/* Animation */
	if ( jQuery('.ltx-towing-car-image').length ) {

		var el = jQuery('.ltx-towing-car-image img');

		jQuery(el).addClass('ltx-sr-id-towing-car ltx-sr ltx-sr-effect-slide_from_top_left ltx-sr-el-block ltx-sr-delay-50 ltx-sr-duration-1200 ltx-sr-sequences-00');
	}

	if ( jQuery('.ltx-rental-car-image').length ) {

		var el = jQuery('.ltx-rental-car-image img');

		jQuery(el).addClass('ltx-sr-id-rental-car ltx-sr ltx-sr-effect-slide_from_top_left ltx-sr-el-block ltx-sr-delay-50 ltx-sr-duration-1200 ltx-sr-sequences-00');
	}	
}

/* Starting countUp function */
function checkCountUp() {

	if (jQuery(".countUp").length){

		jQuery('.countUp').counterUp();
	}
}

/* 
	Scroll Reveal Initialization
	Catches the classes: ltx-sr-fade_in ltx-sr-text_el ltx-sr-delay-200 ltx-sr-duration-300 ltx-sr-sequences-100
*/
function initScrollReveal() {

	if (/Mobi/.test(navigator.userAgent) || jQuery(window).width() < 768) return false;

	window.sr = ScrollReveal();

	var srAnimations = {
		zoom_in: {
			
			opacity : 1,
			scale    : 0.01,
		},
		fade_in: {
			distance: 0,
			opacity : 0,
			scale : 1,
		},
		slide_from_left: {
			distance: '200%',
			origin: 'left',			
		},
		slide_from_right: {
			distance: '150%',
			origin: 'right',			
		},
		slide_from_top: {
			distance: '150%',
			origin: 'top',			
		},
		slide_from_top_left: {
			distance: '90%',
			origin: 'left',	
			opacity : 1,
			scale : 0.01,		
		},		
		slide_from_bottom: {
			distance: '150%',
			origin: 'bottom',			
		},
		slide_rotate: {
			rotate: { x: 0, y: 0, z: 360 },		
		},		
	};

	var srElCfg = {

		block: [''],
		items: ['article', '.item'],
		text_el: ['.heading', '.header', '.subheader', '.btn', 'p', 'ul'],
		list_el: ['li']
	};


	/*
		Parsing elements class to get variables
	*/
	jQuery('.ltx-sr').each(function() {

		var el = jQuery(this),
			srClass = el.attr('class');

		var srId = srClass.match(/ltx-sr-id-(\S+)/),
			srEffect = srClass.match(/ltx-sr-effect-(\S+)/),
			srEl = srClass.match(/ltx-sr-el-(\S+)/),
			srDelay = srClass.match(/ltx-sr-delay-(\d+)/),
			srDuration = srClass.match(/ltx-sr-duration-(\d+)/),
			srSeq = srClass.match(/ltx-sr-sequences-(\d+)/); 

		var cfg = srAnimations[srEffect[1]];

		var srConfig = {

			delay : parseInt(srDelay[1]),
			duration : parseInt(srDuration[1]),
			easing   : 'ease-in-out',
			afterReveal: function (domEl) { jQuery(domEl).css('transition', 'all .3s ease'); }
		}			

		cfg = jQuery.extend({}, cfg, srConfig);

		var initedEls = [];
		jQuery.each(srElCfg[srEl[1]], function(i, e) {

			initedEls.push('.ltx-sr-id-' + srId[1] + ' ' + e);
		});

		sr.reveal(initedEls.join(','), cfg, parseInt(srSeq[1]));
	});
}

/*
	Slider filter 
	Filters element in slider and reinits swiper slider after
*/
function initSliderFilter(swiper) {

	var btns = jQuery('.slider-filter'),
		container = jQuery('.slider-filter-container');

	if (btns.length) {

		btns.on('click', 'a.cat, span.cat, span.img', function() {

			var el = jQuery(this),
				filter = el.data('filter'),
				limit = el.data('limit');

			container.find('.filter-item').show();
			el.parent().parent().find('.cat-active').removeClass('cat-active')
			el.parent().parent().find('.cat-li-active').removeClass('cat-li-active')
			el.addClass('cat-active');
			el.parent().addClass('cat-li-active');

			if (filter !== '') {

				container.find('.filter-item').hide();
				container.find('.filter-item.filter-type-' + filter + '').fadeIn();
			}

			if (swiper !== 0) {

				swiper.slideTo(0, 0);
				swiper.update();
			}

			return false;
		});

		// First Init, Activating first tab
		var firstBtn = btns.find('.cat:first')

		firstBtn.addClass('cat-active');
		firstBtn.parent().addClass('cat-li-active');
		container.find('.filter-item').hide();
		container.find('.filter-item.filter-type-' + firstBtn.data('filter') + '').show();

	}
}

/*
	Rental filter 
	Filters element in rental
*/
function initRentalFilter() {

	var btns = jQuery('.ltx-rental-list .tabs-cats'),
		container = jQuery('.ltx-rental-container');

	if (btns.length) {

		btns.on('click', 'span', function() {

			var el = jQuery(this),
				filter = el.data('filter');

			//container.find('.ltx-filter-item').show();
			el.parent().parent().find('.cat-active').removeClass('cat-active')
			el.parent().parent().find('.cat-li-active').removeClass('cat-li-active')
			el.addClass('cat-active');
			el.parent().addClass('cat-li-active');

			if (filter !== '') {

				container.find('.ltx-filter-item').hide();
				container.find('.ltx-filter-item.ltx-filter-type-' + filter + '').slice(0,6).fadeIn();
			}

			return false;
		});

		// First Init, Activating first tab
		var firstBtn = btns.find('.cat:first')

		firstBtn.addClass('cat-active');
		firstBtn.parent().addClass('cat-li-active');
		container.find('.ltx-filter-item').hide();
		container.find('.ltx-filter-item.ltx-filter-type-' + firstBtn.data('filter') + '').slice(0,6).show();

	}
}


/* Swiper slider initialization */
function initSwiper() {

	var products = jQuery('.products-slider'),
		slidersLtx = jQuery('.slider-sc'),
		servicesEl = jQuery('.services-slider'),
		clientsSwiperEl = jQuery('.testimonials-slider'),
		gallerySwiperEl = jQuery('.swiper-gallery'),
		postGalleryEl = jQuery('.ltx-post-gallery'),
		sliderFc = jQuery('.ltx-slider-fc'),		
		textSwiperEl = jQuery('.swiper-text'),
		schedule = jQuery('.swiper-schedule');
		

	if (slidersLtx.length) {

		if ( slidersLtx.data('autoplay') === 0 ) {

			var autoplay = false;
		}
			else {

			var autoplay = {
				delay: slidersLtx.data('autoplay'),
				disableOnInteraction: false,
			}
		}

	    var slidersSwiper = new Swiper(slidersLtx, {

			speed		: 1000,

			effect : 'fade',
			fadeEffect: { crossFade: true },

			autoplay: autoplay,	

			navigation: {
				nextEl: '.arrow-right',
				prevEl: '.arrow-left',
			},			
	
			pagination : {

				el: '.swiper-pages',
				clickable: true,				
			},

	    });

	    slidersSwiper.update();   
	}

	if (sliderFc.length) {

	    var sliderFcSwiper = new Swiper(sliderFc, {

			direction   : 'horizontal',
			
			navigation: {
				nextEl: '.arrow-right',
				prevEl: '.arrow-left',
			},	
			spaceBetween : 5,

			loop		: true,   
			speed		: 1000,   
			slidesPerView : sliderFc.data('cols'),
		
			autoplay    : sliderFc.data('autoplay'),
			autoplayDisableOnInteraction	: false,
		
	    });

	    sliderFcSwiper.update();
	}


	if (postGalleryEl.length) {
/*
	    var postGallerySwiper = new Swiper(postGalleryEl, {

			navigation: {
				nextEl: '.arrow-right',
				prevEl: '.arrow-left',
			},

			speed		: 1000,   
		
			autoplay    : postGalleryEl.data('autoplay'),
			autoplayDisableOnInteraction	: false,
		
	    });

	    postGallerySwiper.update();
*/
	}

	if (clientsSwiperEl.length) {

		if ( clientsSwiperEl.data('autoplay') === 0 ) {

			var autoplay = false;
		}
			else {

			var autoplay = {
				delay: clientsSwiperEl.data('autoplay'),
				disableOnInteraction: false,
			}
		}

	    var clientsSwiper = new Swiper(clientsSwiperEl, {

	    	initialSlide : 1,
			speed		: 1000,
			slidesPerView : clientsSwiperEl.data('cols'),

			spaceBetween: 30,

			navigation: {
				nextEl: '.arrow-right',
				prevEl: '.arrow-left',
			},
	
			autoplay: autoplay,	
			pagination : {

				el: '.swiper-pages',
				clickable: true,				
			},

	    });

	    clientsSwiper.update();
	}

	if (products.length) {

	    var productsSwiper = new Swiper(products, {

			speed		: 1000,
			slidesPerView : products.data('cols'),	        
			slidesPerGroup : 1,	        

			autoplay    : products.data('autoplay'),
			autoplayDisableOnInteraction	: false,
	    });

	    initSliderFilter(productsSwiper);
	}
		else {

	    initSliderFilter(0);
	}

	if (servicesEl.length) {

		if ( servicesEl.data('autoplay') === 0 ) {

			var autoplay = false;
		}
			else {

			var autoplay = {
				delay: servicesEl.data('autoplay'),
				disableOnInteraction: false,
			}
		}

	    var servicesSwiper = new Swiper(servicesEl, {

			speed		: 1000,
			spaceBetween : 30,

			navigation: {
				nextEl: '.arrow-right',
				prevEl: '.arrow-left',
			},
			slidesPerView : servicesEl.data('cols'),
		
			autoplay: autoplay,	
	    });
	}

	if (gallerySwiperEl.length) {	

	    var gallerySwiperEl = new Swiper(gallerySwiperEl, {
			direction   : 'horizontal',
	        pagination: '.swiper-pagination',
	        paginationClickable: true,		
			autoplay    : 4000,
			autoplayDisableOnInteraction	: false,        
	    });
	}

	if (textSwiperEl.length) {	

	    var textSwiperEl = new Swiper(textSwiperEl, {
			direction   : 'horizontal',
			nextButton	: '.arrow-right',
			prevButton	: '.arrow-left',
			loop		: true,
			autoplay    : 4000,
			autoplayDisableOnInteraction	: false,        
	    });
	}	

	jQuery(window).on('resize', function(){

		var ww = jQuery(window).width(),
			wh = jQuery(window).height();


		if (sliderFc.length && sliderFc.data('cols') >= 3) {

			if (ww > 1200) { sliderFcSwiper.params.slidesPerView = 4; }
			if (ww <= 1200) { sliderFcSwiper.params.slidesPerView = 3; }
			if (ww <= 1000) { sliderFcSwiper.params.slidesPerView = 2; }
			if (ww <= 768) { sliderFcSwiper.params.slidesPerView = 1; }		
		
			sliderFcSwiper.update();			
		}

		if (servicesEl.length && servicesEl.data('cols') >= 3) {

			if (ww > 1600) { servicesSwiper.params.slidesPerView = 3; }
			if (ww <= 1599) { servicesSwiper.params.slidesPerView = 3; }
			if (ww <= 1199) { servicesSwiper.params.slidesPerView = 2; }		
			if (ww <= 768) { servicesSwiper.params.slidesPerView = 1; }		
				
			servicesSwiper.update();			
		}

		if (clientsSwiperEl.length && clientsSwiperEl.data('cols') >= 3) {

			if (ww > 1600) { clientsSwiper.params.slidesPerView = 3; }
			if (ww <= 1599) { clientsSwiper.params.slidesPerView = 2; }
			if (ww <= 1000) { clientsSwiper.params.slidesPerView = 1; }		
		
			clientsSwiper.update();			
		}

		if (products.length && products.data('cols') >= 2) {

			if (ww >= 1600) { productsSwiper.params.slidesPerView = 3; }
			if (ww <= 1599) { productsSwiper.params.slidesPerView = 3; }
			if (ww <= 1199) { productsSwiper.params.slidesPerView = 2; }
			if (ww <= 768) { productsSwiper.params.slidesPerView = 1; }		
		
			productsSwiper.update();			
		}	

	}).resize();

}


/* Masonry initialization */
function initMasonry() {

	jQuery('.masonry').masonry({
	  itemSelector: '.item',
	  columnWidth:  '.item'
	});		

	jQuery('.gallery-inner').masonry({
	  itemSelector: '.mdiv',
	  columnWidth:  '.mdiv'
	});			
}

/* Google maps init */
function initMap() {

	jQuery('.ltx-google-maps').each(function(i, mapEl) {

		mapEl = jQuery(mapEl);
		if (mapEl.length) {

			var uluru = {lat: mapEl.data('lat'), lng: mapEl.data('lng')};
			var map = new google.maps.Map(document.getElementById(mapEl.attr('id')), {
			  zoom: mapEl.data('zoom'),
			  center: uluru,
			  scrollwheel: false,
			  styles: mapStyles
			});

			var marker = new google.maps.Marker({
			  position: uluru,
			  icon: mapEl.data('marker'),
			  map: map
			});
		}
	});
}


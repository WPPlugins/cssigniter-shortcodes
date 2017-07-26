jQuery(window).on("load", function() {
	"use strict";

	/* -----------------------------------------
	 FlexSlider Init
	 ----------------------------------------- */
	var slider = jQuery('.cisc-slider');
	if ( slider.length ) {
		slider.flexslider({
			namespace: 'cisc-flex-',
			selector: '.cisc-slides > li',
			smoothHeight: true,
			prevText: '',
			nextText: '',
			directionNav: false
		});
	}
});

jQuery(document).ready(function($) {
	"use strict";

	/* -----------------------------------------
	 Maps Init
	 ----------------------------------------- */
	var ci_map = $(".cisc-map");
	if ( ci_map.length ) {
		ci_map.each(function() {
			var that = $(this);
			var lat = that.data('lat');
			var lng = that.data('long');
			var tipText = that.data('tooltip');
			var titleText = that.data('title');
			var zoom = that.data('zoom');

			ci_shortcodes_map_init(that.attr('id'), lat, lng, zoom, tipText, titleText);

		});
	}

	/* -----------------------------------------
	 Tabs Init
	 ----------------------------------------- */
	var tabsNav = $(".cisc-tabs-nav").find('a');
	tabsNav.on('click', function(e) {
		e.preventDefault();
		var that = $(this);
		var parentTab = that.parents('.cisc-tabs');
		var tab = that.attr("href");

		that.addClass("cisc-active");
		that.parent().siblings().find('a').removeClass("cisc-active");
		parentTab.find('.cisc-tab-content').not(tab).hide();
		$(tab).fadeIn();
	});

	/* -----------------------------------------
	 Accordion Init
	 ----------------------------------------- */
	var accordion = $(".cisc-accordion");
	var accordionLink = $(".cisc-accordion-panel-title").find('a');
	accordionLink.on('click', function(e) {
		var that = $(this);
		var accordion = that.parents('.cisc-accordion');
		var panel = that.parents('.cisc-accordion-panel');
		var content = that.parents('.cisc-accordion-panel').find('.cisc-accordion-panel-wrap');

		if ( that.hasClass('cisc-active') ) {
			that.removeClass('cisc-active');
			content.slideUp('fast')
		} else {
			accordion.find('.cisc-accordion-panel a').removeClass('cisc-active');
			accordion.find('.cisc-accordion-panel-wrap').slideUp();
			that.addClass('cisc-active');
			content.slideDown('fast');
		}

		e.preventDefault();
	});

	accordion.each(function() {
		var that = $(this);
		var defaultAccordion = that.find('.cisc-open');

		if ( defaultAccordion.length ) {
			defaultAccordion.find('.cisc-accordion-panel-title a').trigger('click');
		} else {
			that.find('.cisc-accordion-panel-title').first().find('a').trigger('click');
		}
	});


});


function ci_shortcodes_map_init(mapID, lat, lng, zoom, tipText, titleText) {
	'use strict';
	var myLatlng = new google.maps.LatLng(lat, lng);
	var mapOptions = {
		scrollwheel: false,
		zoom       : zoom,
		center     : myLatlng,
		mapTypeId  : google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById(mapID), mapOptions);

	var contentString = '<div class="content">' + tipText + '</div>';

	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});

	var marker = new google.maps.Marker({
		position: myLatlng,
		map     : map,
		title   : titleText
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

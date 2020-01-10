/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/main-public.js":
/*!*************************************!*\
  !*** ./resources/js/main-public.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  'use strict';

  var window_width = $(window).width(),
      window_height = window.innerHeight;
  $('.fullscreen').css('height', window_height);
  /*if (document.getElementById('default-select')) {
  	$('select').niceSelect();
  }*/

  $('.toggle_icon').on('click', function () {
    $('body').toggleClass('open');
  });
  /*----------------------------------------------------*/

  /*  Magnific Pop up js
  /*----------------------------------------------------*/
  // for img popup //

  $(".portfolio-area").magnificPopup({
    delegate: '.img-popup',
    type: 'image',
    gallery: {
      enabled: true
    }
  }); // home video //

  $('.video-play-button').magnificPopup({
    type: 'iframe',
    mainClass: 'mfp-fade',
    removalDelay: 160,
    preloader: false,
    fixedContentPos: false
  }); // nice select //

  /*$('select').niceSelect();*/

  /*----------------------------------------------------*/

  /* counter js
  /*----------------------------------------------------*/

  if (document.getElementById("project_counter")) {
    $('.counter').counterUp({
      delay: 10,
      time: 1000
    });
  }
  /*----------------------------------------------------*/

  /*  Brand carousel js
     /*----------------------------------------------------*/

  /*$('.brand-carousel').owlCarousel({
  	items: 4,
  	// autoplay: 2500,
  	loop: true,
  	nav: false,
  	dots: false,
  	responsive: {
  		0: {
  			items: 1
  		},
  		420: {
  			items: 2
  		},
  		575: {
  			items: 3
  		},
  		768: {
  			items: 4
  		},
  		1200: {
  			items: 4
  		}
  	}
  });*/

  /*----------------------------------------------------*/

  /*  Portfolio carousel js
     /*----------------------------------------------------*/

  /*$('.active-gallery-carousel').owlCarousel({
  	items: 2,
  	// autoplay: 2500,
  	loop: true,
  	margin: 30,
  	nav: true,
  	navText:["<img src='img/prev.png'>","<img src='img/next.png'>"],
  	dots: false,
  	responsive: {
  		0: {
  			items: 1
  		},
  		420: {
  			items: 1
  		},
  		575: {
  			items: 2
  		},
  		768: {
  			items: 1
  		},
  		1200: {
  			items: 2
             },
             1680: {
  			items: 3
  		}
  	}
  });*/

  /*----------------------------------------------------*/

  /*  Team carousel js
     /*----------------------------------------------------*/

  /*$('.active-team-carusel').owlCarousel({
  	items: 1,
  	// autoplay: 2500,
  	loop: true,
  	dots: false,
  	nav: true,
  	navText:["<img src='img/prev.png'>","<img src='img/next.png'>"]
  });*/

  /*----------------------------------------------------*/

  /*  Testimonial carousel js
     /*----------------------------------------------------*/

  /*$('.active-testi-carousel').owlCarousel({
  	items: 1,
  	// autoplay: 2500,
  	loop: true,
  	dots: false,
  	nav: true,
  	navText:["<img src='img/prev.png'>","<img src='img/next.png'>"]
  });*/
  // $(document).ready(function() {
  // 	$('#mc_embed_signup').find('form').ajaxChimp();
  // });

  /*----------------------------------------------------*/

  /*  Google map js
  /*----------------------------------------------------*/


  if ($('#mapBox').length) {
    var $lat = $('#mapBox').data('lat');
    var $lon = $('#mapBox').data('lon');
    var $zoom = $('#mapBox').data('zoom');
    var $marker = $('#mapBox').data('marker');
    var $info = $('#mapBox').data('info');
    var $markerLat = $('#mapBox').data('mlat');
    var $markerLon = $('#mapBox').data('mlon');
    var map = new GMaps({
      el: '#mapBox',
      lat: $lat,
      lng: $lon,
      scrollwheel: false,
      scaleControl: true,
      streetViewControl: false,
      panControl: true,
      disableDoubleClickZoom: true,
      mapTypeControl: false,
      zoom: $zoom,
      styles: [{
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [{
          "color": "#dcdfe6"
        }]
      }, {
        "featureType": "transit",
        "stylers": [{
          "color": "#808080"
        }, {
          "visibility": "off"
        }]
      }, {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [{
          "visibility": "on"
        }, {
          "color": "#dcdfe6"
        }]
      }, {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [{
          "color": "#ffffff"
        }]
      }, {
        "featureType": "road.local",
        "elementType": "geometry.fill",
        "stylers": [{
          "visibility": "on"
        }, {
          "color": "#ffffff"
        }, {
          "weight": 1.8
        }]
      }, {
        "featureType": "road.local",
        "elementType": "geometry.stroke",
        "stylers": [{
          "color": "#d7d7d7"
        }]
      }, {
        "featureType": "poi",
        "elementType": "geometry.fill",
        "stylers": [{
          "visibility": "on"
        }, {
          "color": "#ebebeb"
        }]
      }, {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [{
          "color": "#a7a7a7"
        }]
      }, {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [{
          "color": "#ffffff"
        }]
      }, {
        "featureType": "road.arterial",
        "elementType": "geometry.fill",
        "stylers": [{
          "color": "#ffffff"
        }]
      }, {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [{
          "visibility": "on"
        }, {
          "color": "#efefef"
        }]
      }, {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [{
          "color": "#696969"
        }]
      }, {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [{
          "visibility": "on"
        }, {
          "color": "#737373"
        }]
      }, {
        "featureType": "poi",
        "elementType": "labels.icon",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "poi",
        "elementType": "labels",
        "stylers": [{
          "visibility": "off"
        }]
      }, {
        "featureType": "road.arterial",
        "elementType": "geometry.stroke",
        "stylers": [{
          "color": "#d6d6d6"
        }]
      }, {
        "featureType": "road",
        "elementType": "labels.icon",
        "stylers": [{
          "visibility": "off"
        }]
      }, {}, {
        "featureType": "poi",
        "elementType": "geometry.fill",
        "stylers": [{
          "color": "#dadada"
        }]
      }]
    });
  }
});

/***/ }),

/***/ 3:
/*!*******************************************!*\
  !*** multi ./resources/js/main-public.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\OpenServer\OSPanel\domains\admount.loc\resources\js\main-public.js */"./resources/js/main-public.js");


/***/ })

/******/ });
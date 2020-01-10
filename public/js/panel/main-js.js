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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/panel/main-js.js":
/*!***************************************!*\
  !*** ./resources/js/panel/main-js.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

jQuery(document).ready(function ($) {
  'use strict'; // ==============================================================
  // Notification list
  // ==============================================================

  if ($(".notification-list").length) {
    $('.notification-list').slimScroll({
      height: '250px'
    });
  } // ==============================================================
  // Menu Slim Scroll List
  // ==============================================================


  if ($(".menu-list").length) {
    $('.menu-list').slimScroll({
      height: '100%'
    });
  } // ==============================================================
  // Activation of menu items
  // ==============================================================


  $('.menu-list div.submenu li a.active').closest('div.submenu').addClass('show').siblings('a.nav-link').removeClass('collapsed').attr('aria-expanded', 'true'); // ==============================================================
  // Sidebar scrollnavigation
  // ==============================================================

  if ($(".sidebar-nav-fixed a").length) {
    $('.sidebar-nav-fixed a') // Remove links that don't actually link to anything
    .click(function (event) {
      // On-page links
      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        // Figure out element to scroll to
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']'); // Does a scroll target exist?

        if (target.length) {
          // Only prevent default if animation is actually gonna happen
          event.preventDefault();
          $('html, body').animate({
            scrollTop: target.offset().top - 90
          }, 1000, function () {
            // Callback after animation
            // Must change focus!
            var $target = $(target);
            $target.focus();

            if ($target.is(":focus")) {
              // Checking if the target was focused
              return false;
            } else {
              $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable

              $target.focus(); // Set focus again
            }

            ;
          });
        }
      }

      ;
      $('.sidebar-nav-fixed a').each(function () {
        $(this).removeClass('active');
      });
      $(this).addClass('active');
    });
  } // ==============================================================
  // tooltip
  // ==============================================================


  if ($('[data-toggle="tooltip"]').length) {
    $('[data-toggle="tooltip"]').tooltip();
  } // ==============================================================
  // popover
  // ==============================================================


  if ($('[data-toggle="popover"]').length) {
    $('[data-toggle="popover"]').popover();
  } // ==============================================================
  // Chat List Slim Scroll
  // ==============================================================


  if ($('.chat-list').length) {
    $('.chat-list').slimScroll({
      color: 'false',
      width: '100%'
    });
  } // ==============================================================
  // Video link
  // ==============================================================


  $('.video-play-link').magnificPopup({
    type: 'iframe',
    mainClass: 'mfp-fade',
    removalDelay: 160,
    preloader: false,
    fixedContentPos: false
  }); // ==============================================================
  // dropzone script
  // ==============================================================
  //     if ($('.dz-clickable').length) {
  //            $(".dz-clickable").dropzone({ url: "/file/post" });
  // }
}); // AND OF JQUERY
// $(function() {
//     "use strict";
// var monkeyList = new List('test-list', {
//    valueNames: ['name']
// });
// var monkeyList = new List('test-list-2', {
//    valueNames: ['name']
// });
// });

/***/ }),

/***/ 2:
/*!*********************************************!*\
  !*** multi ./resources/js/panel/main-js.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! D:\OpenServer\OSPanel\domains\admount.loc\resources\js\panel\main-js.js */"./resources/js/panel/main-js.js");


/***/ })

/******/ });
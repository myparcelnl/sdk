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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./views/js/myparcelinit.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./views/js/myparcelinit.js":
/*!**********************************!*\
  !*** ./views/js/myparcelinit.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("$(document).ready(function () {\n  var initializeMyParcelForm = function initializeMyParcelForm($option) {\n    if (!$option.length) {\n      return false;\n    }\n\n    var $wrapper = $option.closest('.delivery-option').next().find('.myparcel-delivery-options-wrapper');\n\n    if (!$wrapper.length) {\n      return false;\n    }\n\n    var $currentCarrier = $('.delivery-option input:checked');\n    var currentCarrierId = $currentCarrier.val();\n    $.ajax({\n      url: '/index.php?fc=module&module=myparcelbe&controller=checkout&id_carrier=' + currentCarrierId,\n      dataType: \"json\",\n      success: function success(data) {\n        window.MyParcelConfig = data;\n        var form = document.querySelector('.myparcel-delivery-options');\n\n        if (form) {\n          form.remove();\n          $wrapper[0].innerHTML = '<div id=\"myparcel-delivery-options\"></div>';\n          setTimeout(function () {//document.dispatchEvent(new Event('myparcel_render_delivery_options'))\n          }, 500);\n        } else {\n          $wrapper[0].innerHTML = '<div id=\"myparcel-delivery-options\"></div>';\n          document.dispatchEvent(new Event('myparcel_update_delivery_options'));\n        }\n      }\n    });\n  };\n\n  if (typeof prestashop !== 'undefined') {\n    prestashop.on('updatedDeliveryForm', function (event) {\n      var $parent = $(event.deliveryOption);\n      initializeMyParcelForm($('input:checked', $parent));\n    });\n  } //initializeMyParcelForm($('.delivery-option input:checked'))\n  //document.addEventListener('myparcel_updated_delivery_options', (event) => updateMypaInput(event.detail))\n\n});\n\n//# sourceURL=webpack:///./views/js/myparcelinit.js?");

/***/ })

/******/ });
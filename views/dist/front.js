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

eval("document.addEventListener(\"DOMContentLoaded\", function (event) {\n  var initializeMyParcelForm = function initializeMyParcelForm(carrier) {\n    if (!carrier || !carrier.length || !carrier.find('input:checked')) {\n      return;\n    }\n\n    var carrierId = carrier.find('input:checked')[0].value.split(',').join('');\n    var wrapper = carrier[0].nextElementSibling.querySelector('.myparcel-delivery-options-wrapper');\n\n    if (!wrapper) {\n      return;\n    }\n\n    $.ajax({\n      url: '/index.php?fc=module&module=myparcelbe&controller=checkout&id_carrier=' + carrierId,\n      dataType: \"json\",\n      success: function success(data) {\n        window.MyParcelConfig = data;\n        var form = document.querySelector('.myparcel-delivery-options');\n\n        if (form) {\n          form.remove();\n        }\n\n        wrapper.innerHTML = '<div id=\"myparcel-delivery-options\"></div>';\n        document.dispatchEvent(new Event('myparcel_render_delivery_options'));\n      }\n    });\n  };\n\n  var updateMypaInput = function updateMypaInput(dataObj) {\n    var $input = $('#mypa-input');\n\n    if (!$input.length) {\n      $input = $('<input type=\"hidden\" class=\"mypa-post-nl-data\" id=\"mypa-input\" name=\"myparcel-delivery-options\" />');\n      var $wrapper = $('.delivery-option input[type=\"radio\"]:checked').closest('.delivery-option').next().find('.myparcel-delivery-options-wrapper');\n\n      if ($wrapper.length) {\n        $wrapper.append($input);\n      }\n    }\n\n    var dataString = JSON.stringify(dataObj);\n    $input.val(dataString);\n  }; // On change\n\n\n  if (typeof prestashop !== 'undefined') {\n    prestashop.on('updatedDeliveryForm', function (event) {\n      initializeMyParcelForm(event.deliveryOption);\n    });\n  }\n\n  document.addEventListener('myparcel_updated_delivery_options', function (event) {\n    return updateMypaInput(event.detail);\n  }); // Init\n\n  initializeMyParcelForm($('.delivery-option input:checked').closest('.delivery-option'));\n});\n\n//# sourceURL=webpack:///./views/js/myparcelinit.js?");

/***/ })

/******/ });
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
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$(document).ready(function () {
  var initialized = false;

  var initializeMyParcelForm = async function initializeMyParcelForm($option) {
    if (!$option.length) {
      console.log('option empty');
      return false;
    }

    var $wrapper = $option.closest('.delivery-option').next().find('.myparcel-delivery-options-wrapper');
    if (!$wrapper.length) {
      console.log('wrapper empty');
      return false;
    }

    var $currentCarrier = $('.delivery-option input:checked');
    var currentCarrierId = $currentCarrier.val();
    $.ajax({
      url: "/index.php?fc=module&module=myparcel&controller=checkout&id_carrier=" + currentCarrierId,
      dataType: "json",
      success: function success(data) {
        window.MyParcelConfig = data;

        if (initialized) {
          var $form = $('.myparcel-delivery-options');
          $form.outerHTML = '<div id="myparcel-delivery-options"></div>';
          var $input = $('#mypa-input');

          if ($form.length) {
            $form.detach().appendTo($wrapper);
            $input.detach().appendTo($wrapper);
            document.dispatchEvent(new Event('myparcel_update_delivery_options'));
            return true;
          } else {
            initialized = false;
          }
        }

        var container = document.createElement("div");
        container.id = 'myparcel-delivery-options';
        $wrapper[0].appendChild(container);

        var input = document.createElement("input");
        input.id = 'mypa-input';
        input.classList.add('mypa-post-nl-data');
        input.style = "display:none;";
        input.name = "myparcel-delivery-options";
        $wrapper[0].appendChild(input);

        document.dispatchEvent(new Event('myparcel_update_delivery_options'));

        $wrapper.addClass('myparcel-delivery-options-initialized');
        initDefaults();
        initialized = true;
      },
      error: function error(err) {
        //TODO: display an error somehow
      }
    });
  };

  var updateMypaInput = function updateMypaInput(dataObj) {
    var $input = $('#mypa-input');
    if (!$input.length) {
      return false;
    }

    var dataString = JSON.stringify(dataObj);

    $input.val(dataString);
  };

  var initDefaults = function initDefaults() {
    setTimeout(function () {
      if (typeof deliverySettingsMP === 'undefined' || deliverySettingsMP === null) {
        return false;
      }
      var $parent = $('.myparcel-delivery-options-wrapper.myparcel-delivery-options-initialized');
      if (deliverySettingsMP.isPickup) {
        $('#myparcel-delivery-options__delivery--pickup', $parent).trigger('click');
      }
      if (deliverySettingsMP.deliveryType === 'morning') {
        $('#myparcel-delivery-options__deliveryMoment--morning', $parent).trigger('click');
      }
      if (deliverySettingsMP.deliveryType === 'evening') {
        $('#myparcel-delivery-options__deliveryMoment--evening', $parent).trigger('click');
      }
      setTimeout(function () {
        if (deliverySettingsMP.shipmentOptions.only_recipient) {
          $('#myparcel-delivery-options__shipmentOptions--only_recipient', $parent).prop('checked', true);
        } else {
          $('#myparcel-delivery-options__shipmentOptions--only_recipient', $parent).prop('checked', false);
        }
        if (deliverySettingsMP.shipmentOptions.signature) {
          $('#myparcel-delivery-options__shipmentOptions--signature', $parent).prop('checked', true);
        } else {
          $('#myparcel-delivery-options__shipmentOptions--signature', $parent).prop('checked', false);
        }
      }, 100);
    }, 2000); // TODO: find an event to bind to
  };

  $(document).on('change', '.delivery-option input', function () {
    initializeMyParcelForm($(this));
  });
  initializeMyParcelForm($('.delivery-option input:checked'));

  document.addEventListener('myparcel_updated_delivery_options', function (event) {
    return updateMypaInput(event.detail);
  });
});

/***/ })
/******/ ]);
!function(e){var n={};function t(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,t),o.l=!0,o.exports}t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:r})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,n){if(1&n&&(e=t(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(t.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var o in e)t.d(r,o,function(n){return e[n]}.bind(null,o));return r},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=0)}([function(e,n){document.addEventListener("DOMContentLoaded",(function(e){var n=function(e){if(e&&e.length&&e.find("input:checked")){var n=e.find("input:checked")[0].value.split(",").join(""),r=e[0].nextElementSibling.querySelector(".myparcel-delivery-options-wrapper");r&&$.ajax({url:"/index.php?fc=module&module=myparcelbe&controller=checkout&id_carrier="+n,dataType:"json",success:function(e){window.MyParcelConfig=e;var n=document.querySelector(".myparcel-delivery-options");n&&n.remove(),r.innerHTML='<div id="myparcel-delivery-options"></div>',t(e.delivery_settings)}})}},t=function(e){var n=$("#mypa-input");if(!n.length){n=$('<input type="hidden" class="mypa-post-nl-data" id="mypa-input" name="myparcel-delivery-options" />');var t=$('.delivery-option input[type="radio"]:checked').closest(".delivery-option").next().find(".myparcel-delivery-options-wrapper");t.length&&t.append(n)}var r=JSON.stringify(e);n.val(r),document.dispatchEvent(new Event("myparcel_render_delivery_options"))};"undefined"!=typeof prestashop&&prestashop.on("updatedDeliveryForm",(function(e){n(e.deliveryOption)})),n($(".delivery-option input:checked").closest(".delivery-option"))})),prestashop.on("changedCheckoutStep",(function(e){var n=e.event,t=$(n.currentTarget);if(console.log(t),!t.hasClass("-current")){var r=$(".checkout-step.-current");console.log(r),r.length||(t.addClass("-current"),t.addClass("js-current-step"))}}))}]);
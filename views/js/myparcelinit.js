document.addEventListener("DOMContentLoaded", function (event) {
    let initializeMyParcelForm = function (carrier) {
        if (!carrier || !carrier.length || !carrier.find('input:checked')) {
            return;
        }

        let carrierId = carrier.find('input:checked')[0].value.split(',').join('');
        let wrapper = carrier[0].nextElementSibling.querySelector('.myparcel-delivery-options-wrapper');

        if (!wrapper) {
            return;
        }

        $.ajax({
            url: myparcel_carrier_init_url + '?id_carrier=' + carrierId,
            dataType: "json",
            success: function (data) {
                window.MyParcelConfig = data;

                let form = document.querySelector('.myparcel-delivery-options');
                if (form) {
                    form.remove();
                }
                wrapper.innerHTML = '<div id="myparcel-delivery-options"></div>';
                updateMypaInput(data.delivery_settings);
            }
        });
    }

    let updateMypaInput = function(dataObj) {
        let $deliveryInput = $('.delivery-option input[type="radio"]:checked');
        let $input = $('#mypa-input');
        if (!$input.length) {
            $input = $('<input type="hidden" class="mypa-post-nl-data" id="mypa-input" name="myparcel-delivery-options" />');
            let $wrapper = $deliveryInput
              .closest('.delivery-option')
              .next()
              .find('.myparcel-delivery-options-wrapper');
            if ($wrapper.length) {
                $wrapper.append($input);
            }
        }

      let dataString = JSON.stringify(dataObj)

      $input.val(dataString);

      let $checkoutDeliverStep = $('#checkout-delivery-step');
      let isOnDeliverStep = $checkoutDeliverStep.hasClass('js-current-step') || $checkoutDeliverStep.hasClass('-current');
      if(isOnDeliverStep) {
        $input.trigger('change');
      }
      document.dispatchEvent(new Event('myparcel_render_delivery_options'));
    }

    // On change
    if (typeof prestashop !== 'undefined') {
        prestashop.on('updatedDeliveryForm', function (event) {
            initializeMyParcelForm(event.deliveryOption);
        });
    }

    // Init
    initializeMyParcelForm($('.delivery-option input:checked').closest('.delivery-option'));

  document.addEventListener(
    'myparcel_updated_delivery_options',
    (event) => {
      if(event.detail) {
        updateMypaInput(event.detail);
      }
    }
  );
});

//workaround for the buggy parestashop core
prestashop.on('changedCheckoutStep', function(values) {
  let event = values.event;
  let $currentTarget = $(event.currentTarget);
  if(!$currentTarget.hasClass('-current')) {
    let $activeStep = $('.checkout-step.-current');
    if(!$activeStep.length) {
      $currentTarget.addClass('-current');
      $currentTarget.addClass('js-current-step');
    }
  }
});


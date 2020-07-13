$(document).ready(function() {
    let initialized = false;

    let initializeMyParcelForm = async function($option) {
        if (!$option.length) {
            return false;
        }

        let $wrapper = $option.closest('.delivery-option').next().find('.myparcel-delivery-options-wrapper');
        if (!$wrapper.length) {
            return false;
        }

        let $currentCarrier = $('.delivery-option input:checked');
        let currentCarrierId = $currentCarrier.val();
        $.ajax({
          url: '/index.php?fc=module&module=myparcelbe&controller=checkout&id_carrier=' + currentCarrierId,
          dataType: "json",
          success: function(data) {
            window.MyParcelConfig = data;

            if (initialized) {
              let $form = $('.myparcel-delivery-options');
              $form.outerHTML = '<div id="myparcel-delivery-options"></div>';
              let $input = $('#mypa-input');

              if ($form.length) {
                $form.detach().appendTo($wrapper);
                $input.detach().appendTo($wrapper);
                document.dispatchEvent(new Event('myparcel_update_delivery_options'));
                return true;
              } else {
                initialized = false;
              }
            }

            let container = document.createElement('div');
            container.id = 'myparcel-delivery-options';
            $wrapper[0].appendChild(container);

            let input = document.createElement('input');
            input.id = 'mypa-input';
            input.classList.add('mypa-post-nl-data');
            input.style = 'display:none;';
            input.name = 'myparcel-delivery-options';
            $wrapper[0].appendChild(input);

            document.dispatchEvent(new Event('myparcel_update_delivery_options'));

            $wrapper.addClass('myparcel-delivery-options-initialized');
            initDefaults();
            initialized = true;
          },
          error: function(err) {
            //TODO: display an error somehow
          }
        });
    };

    let updateMypaInput = function(dataObj) {
        let $input = $('#mypa-input');
        if(!$input.length) {
            return false;
        }

        let dataString = JSON.stringify(dataObj)

        $input.val(dataString);
    }

    let initDefaults = function() {
      setTimeout(function() {
        if (typeof deliverySettingsMP === 'undefined' || deliverySettingsMP === null) {
          return false;
        }
        let $parent = $('.myparcel-delivery-options-wrapper.myparcel-delivery-options-initialized');
        if (deliverySettingsMP.isPickup) {
          $('#myparcel-delivery-options__delivery--deliver', $parent).prop('checked', false);
          //$('#myparcel-delivery-options__delivery--pickup', $parent).prop('checked', true);
        }
        if (deliverySettingsMP.deliveryType === 'morning') {
          $('#myparcel-delivery-options__deliveryMoment--morning', $parent).prop('checked', true);
          $('#myparcel-delivery-options__deliveryMoment--standard', $parent).prop('checked', false);
          $('#myparcel-delivery-options__deliveryMoment--evening', $parent).prop('checked', false);
        }
        if (deliverySettingsMP.deliveryType === 'evening') {
          $('#myparcel-delivery-options__deliveryMoment--morning', $parent).prop('checked', false);
          $('#myparcel-delivery-options__deliveryMoment--standard', $parent).prop('checked', false);
          $('#myparcel-delivery-options__deliveryMoment--evening', $parent).prop('checked', true);
        }
        setTimeout(function() {
          if (typeof deliverySettingsMP.shipmentOptions !== 'undefined') {
            if (typeof deliverySettingsMP.shipmentOptions.only_recipient !== 'undefined') {
              if (deliverySettingsMP.shipmentOptions.only_recipient) {
                $('#myparcel-delivery-options__shipmentOptions--only_recipient', $parent).prop('checked', true);
              } else {
                $('#myparcel-delivery-options__shipmentOptions--only_recipient', $parent).prop('checked', false);
              }
            }
            if (typeof deliverySettingsMP.shipmentOptions.signature !== 'undefined') {
              if (deliverySettingsMP.shipmentOptions.signature) {
                $('#myparcel-delivery-options__shipmentOptions--signature', $parent).prop('checked', true);
              } else {
                $('#myparcel-delivery-options__shipmentOptions--signature', $parent).prop('checked', false);
              }
            }
          }
        }, 100);
      }, 2000);// TODO: find an event to bind to
    }

    $(document).on('change', '.delivery-option input', function() {
        //initializeMyParcelForm($(this));
    });
    if (typeof prestashop !== 'undefined') {
      prestashop.on('updatedDeliveryForm', function (event) {
        let $parent = $(event.deliveryOption);
        initializeMyParcelForm($('input:checked', $parent));
      });
    }
    initializeMyParcelForm($('.delivery-option input:checked'));

    document.addEventListener('myparcel_updated_delivery_options', (event) => updateMypaInput(event.detail));
});

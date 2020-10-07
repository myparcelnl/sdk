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
            url: '/index.php?fc=module&module=myparcelbe&controller=checkout&id_carrier=' + carrierId,
            dataType: "json",
            success: function (data) {
                window.MyParcelConfig = data;

                let form = document.querySelector('.myparcel-delivery-options');
                if (form) {
                    form.remove();
                }
                wrapper.innerHTML = '<div id="myparcel-delivery-options"></div>';
                document.dispatchEvent(new Event('myparcel_render_delivery_options'));
            }
        });
    }

    let updateMypaInput = function(dataObj) {
        let $input = $('#mypa-input');
        if (!$input.length) {
            $input = $('<input type="hidden" class="mypa-post-nl-data" id="mypa-input" name="myparcel-delivery-options" />');
            let $wrapper = $('.delivery-option input[type="radio"]:checked')
              .closest('.delivery-option')
              .next()
              .find('.myparcel-delivery-options-wrapper');
            if ($wrapper.length) {
                $wrapper.append($input);
            }
        }

        let dataString = JSON.stringify(dataObj)

        $input.val(dataString);
    }

    // On change
    if (typeof prestashop !== 'undefined') {
        prestashop.on('updatedDeliveryForm', function (event) {
            initializeMyParcelForm(event.deliveryOption);
        });
    }

    document.addEventListener(
      'myparcel_updated_delivery_options',
      (event) => updateMypaInput(event.detail)
    );

    // Init
    initializeMyParcelForm($('.delivery-option input:checked').closest('.delivery-option'));
});
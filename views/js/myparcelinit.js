$(document).ready(function() {
    let initialized = false;

    let initializeMyParcelForm = async function($option) {
        if(!$option.length) {
            console.log('option empty');
            return false;
        }

        let $wrapper = $option.closest('.delivery-option').next().find('.myparcel-delivery-options-wrapper');
        if(!$wrapper.length) {
            console.log('wrapper empty');
            return false;
        }

        let $currentCarrier = $('.delivery-option input:checked');
        let currentCarrierId = $currentCarrier.val();
        $.ajax({
          url: "/index.php?fc=module&module=myparcel&controller=checkout&id_carrier=" + currentCarrierId,
          dataType: "json",
          success: function(data) {
            window.MyParcelConfig = data;

            if(initialized) {
              let $form = $('.myparcel-delivery-options');
              let $input = $('#mypa-input');

              if($form.length) {
                $form.detach().appendTo($wrapper);
                $input.detach().appendTo($wrapper);
                document.dispatchEvent(new Event('myparcel_update_delivery_options'));
                return true;
              } else {
                initialized = false;
              }
            }

            let container = document.createElement("div");
            container.id = 'myparcel-delivery-options';
            $wrapper[0].appendChild(container);

            let input = document.createElement("input");
            input.id = 'mypa-input';
            input.classList.add('mypa-post-nl-data');
            input.style = "display:none;";
            input.name = "myparcel-delivery-options";
            $wrapper[0].appendChild(input);

            document.dispatchEvent(new Event('myparcel_update_delivery_options'));

            $wrapper.addClass('myparcel-delivery-options-initialized');
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

    $(document).on('change', '.delivery-option input', function() {
        initializeMyParcelForm($(this));
    });
    initializeMyParcelForm($('.delivery-option input:checked'));

    document.addEventListener('myparcel_updated_delivery_options', (event) => updateMypaInput(event.detail));
});

$(document).ready(function() {
    let initialized = false;

    $.ajax({
        url: "/index.php?fc=module&module=myparcel&controller=checkout",
        dataType: "json",
        success: function(data) {
            //populate form data
            window.MyParcelConfig = data;

            //initialize a form if the shipping method is already checked
            initializeMyParcelForm($('.delivery-option input:checked'));
        },
        error: function(err) {
            //TODO: display an error somehow
        }
    });

    let initializeMyParcelForm = function($option) {
        if(!$option.length) {
            console.log('option empty');
            return false;
        }

        let $wrapper = $option.closest('.delivery-option').next().find('.myparcel-delivery-options-wrapper');
        if(!$wrapper.length) {
            console.log('wrapper empty');
            return false;
        }

        if(initialized) {
            let $form = $('.myparcel-delivery-options');

            if($form.length) {
                $form.detach().appendTo($wrapper);
                return true;
            } else {
                initialized = false;
            }
        }

        let container = document.createElement("div");
        container.id = 'myparcel-delivery-options';
        $wrapper[0].appendChild(container);
        $wrapper.addClass('myparcel-delivery-options-initialized');
        initialized = true;

        document.dispatchEvent(new Event('myparcel_update_delivery_options'));
    }

    $(document).on('change', '.delivery-option input', function() {
        initializeMyParcelForm($(this));
    });
});
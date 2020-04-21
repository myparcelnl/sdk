$(document).ready(function() {
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

        let $wrapper = $option.closest('.delivery-option').next().find('.myparcel-delivery-options-wrapper').not('.myparcel-delivery-options-initialized');
        if(!$wrapper.length) {
            console.log('wrapper empty');
            return false;
        }

        let container = document.createElement("div");
        container.id = 'myparcel-delivery-options';
        $wrapper[0].appendChild(container);
        $wrapper.addClass('myparcel-delivery-options-initialized');

        document.dispatchEvent(new Event('myparcel_update_delivery_options'));
    }

    $(document).on('change', '.delivery-option input', function() {
        initializeMyParcelForm($(this));
    });
});
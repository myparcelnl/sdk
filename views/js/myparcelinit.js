document.addEventListener("DOMContentLoaded", function (event) {
    let initializeMyParcelForm = function (carrier) {
        if (!carrier || !carrier.length || !carrier.find('input:checked')) {
            return;
        }

        let carrierId = carrier.find('input:checked')[0].value.split(',').join('')
        let wrapper = carrier[0].nextElementSibling.querySelector('.myparcel-delivery-options-wrapper')

        if (!wrapper) {
            return;
        }

        $.ajax({
            url: '/index.php?fc=module&module=myparcelbe&controller=checkout&id_carrier=' + carrierId,
            dataType: "json",
            success: function (data) {
                window.MyParcelConfig = data

                let form = document.querySelector('.myparcel-delivery-options')
                if (form) {
                    form.remove()
                    wrapper.innerHTML = '<div id="myparcel-delivery-options"></div>'
                    document.dispatchEvent(new Event('myparcel_render_delivery_options'))
                } else {
                    wrapper.innerHTML = '<div id="myparcel-delivery-options"></div>'
                    document.dispatchEvent(new Event('myparcel_update_delivery_options'))
                }



            }
        })
    }

    // On change
    if (typeof prestashop !== 'undefined') {
        prestashop.on('updatedDeliveryForm', function (event) {
            initializeMyParcelForm(event.deliveryOption)
        })
    }

    // Init
    initializeMyParcelForm($('.delivery-option input:checked').closest('.delivery-option'))
})

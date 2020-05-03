window.addEventListener('load', function() {
    let bulk = document.querySelector('.adminorders .bulk-actions .dropdown-menu')
    if (!bulk) {
        return
    }


    let addBulkOption = function(link) {
        let item = document.createElement('li');
        item.appendChild(link);
        bulk.appendChild(item);
    };

    let addBulkCreateLabel = function() {
        let link = document.createElement('a');

        link.innerHTML = '<i class="icon-download"></i> ' + "Create label";
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            ids = {};
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(e => {
                ids[e.value] = document.querySelector('button[data-order-id="'+e.value+'"]').dataset.labelOptions;
            });

            $.ajax({
                method: "POST",
                url: create_labels_bulk_route,
                data: {
                    data: ids
                }
            }).done((result) => {
                // window.location.reload();
            }).fail(() => {

            });
        });

        addBulkOption(link);
    };

    let addBulkRefreshLabel = function() {
        let link = document.createElement('a');

        link.innerHTML = '<i class="icon-download"></i> ' + "Refresh labels";
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            ids = [];
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(e => {
                ids.push(e.value);
            });

            $.ajax({
                method: "POST",
                url: refresh_labels_bulk_route,
                data: {
                    order_ids: ids
                }
            }).done((result) => {
                //window.location.reload();
            }).fail(() => {

            });
        });

        addBulkOption(link);
    };

    let addBulkPrintLabel = function() {
        let link = document.createElement('a');

        link.innerHTML = '<i class="icon-download"></i> ' + "Print labels";
        link.setAttribute('data-toggle', 'modal');
        link.setAttribute('data-target', '#bulk-print');
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let ids = [];
            $('#print-bulk-form').find('input[name="order_ids[]"]').remove();
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(function(e) {
                let $labelIdInput = $('<input type="hidden" name="order_ids[]" value="' + e.value + '">');
                $labelIdInput.prependTo('#bulk-print form');
            });
        });

        addBulkOption(link);
    };

    addBulkPrintLabel();
    addBulkRefreshLabel();
    addBulkCreateLabel();
});

document.addEventListener("DOMContentLoaded", () => {
    $('button[data-target="#print"]').click(function(){
        var id = $(this).data('label-id');
        $('#id_label').val(id);
    });
    $('button[data-target="#create"]').click(function(){
        var id = $(this).data('order-id'),
            options = $(this).data('label-options');
        console.log(options);
        $('#order_id').val(id);
        $('#package-type').val(options.package_type);
        if (options.only_to_recepient == true) {
            $("#MY_PARCEL_RECIPIENT_ONLY").prop("checked", true)
        }
        if (options.age_check == true) {
            $("#MY_PARCEL_AGE_CHECK").prop("checked", true)
        }
        if (options.signature == true) {
            $("#MY_PARCEL_SIGNATURE_REQUIRED").prop("checked", true)
        }
        if (options.insurance) {
            $("#MY_PARCEL_INSURANCE").prop("checked", true)
        }
    });
    $('#print_button').click(function () {
        $('#print-form').submit();
    });
    $('#print-bulk-button').click(function () {
        $('#print-bulk-form').submit();
    });

    $('#add').click(function () {
        $.ajax({
            method: "POST",
            url: create_label_action,
            data: $('#print-modal :input').serialize()
        }).done((result) => {
            //window.location.reload();
        }).fail(() => {

        });
    });
});



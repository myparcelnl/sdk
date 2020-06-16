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

        link.innerHTML = '<i class="icon-download"></i> ' + create_label_text;
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let ids = {};
            let idsArray = [];
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(e => {
                if (document.querySelector('button[data-order-id="'+e.value+'"]')){
                    ids[e.value] = document.querySelector('button[data-order-id="'+e.value+'"]').dataset.labelOptions;
                    idsArray.push(e.value);
                }
            });
            if (!idsArray.length) {
                $('#ajax_confirmation').before(
                    '<div class="alert alert-danger">' +
                    '<button type="button" class="close" data-dismiss="alert">×</button>'+no_order_selected_error+'</div>'
                );
                $.scrollTo(0);
                return;
            }
            $.ajax({
                method: "POST",
                url: create_labels_bulk_route,
                data: {
                    data: ids
                }
            }).done((result) => {
                 window.location.reload();
            }).fail((error) => {
                $('#ajax_confirmation').before(
                    '<div class="alert alert-danger">' +
                    '<button type="button" class="close" data-dismiss="alert">×</button>'+error.responseText+'</div>'
                )
            });
        });

        addBulkOption(link);
    };

    let addBulkRefreshLabel = function() {
        let link = document.createElement('a');

        link.innerHTML = '<i class="icon-download"></i> ' + refresh_labels_text;
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let ids = [];
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(e => {
                ids.push(e.value);
            });

            if (!ids.length) {
                $('#ajax_confirmation').before(
                  '<div class="alert alert-danger">' +
                  '<button type="button" class="close" data-dismiss="alert">×</button>'+no_order_selected_error+'</div>'
                );
                $.scrollTo(0);
                return false;
            }

            $.ajax({
                method: "POST",
                url: refresh_labels_bulk_route,
                data: {
                    order_ids: ids
                }
            }).done((result) => {
                window.location.reload();
            }).fail((error) => {
                $('#ajax_confirmation').before(
                    '<div class="alert alert-danger">' +
                    '<button type="button" class="close" data-dismiss="alert">×</button>'+error.responseText+'</div>'
                )
            });
        });

        addBulkOption(link);
    };

    let addBulkPrintLabel = function() {
        let link = document.createElement('a');

        link.innerHTML = '<i class="icon-download"></i> ' + print_labels_text;
        if (typeof prompt_for_label_position !== 'undefined' && parseInt(prompt_for_label_position) === 1) {
            link.setAttribute('data-toggle', 'modal');
            link.setAttribute('data-target', '#bulk-print');
        }
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            $('#print-bulk-form').find('input[name="order_ids[]"]').remove();
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(function(e) {
                let $labelIdInput = $('<input type="hidden" name="order_ids[]" value="' + e.value + '">');
                $labelIdInput.prependTo('#bulk-print form');
            });
            if (typeof prompt_for_label_position === 'undefined' || parseInt(prompt_for_label_position) !== 1) {
                $('#print-bulk-button').trigger('click');
            }
        });

        addBulkOption(link);
    };

    addBulkPrintLabel();
    addBulkRefreshLabel();
    addBulkCreateLabel();
});

document.addEventListener("DOMContentLoaded", () => {
    $('button.btn-print-label').click(function(){
        var id = $(this).data('label-id');
        $('#id_label').val(id);
        if (!$(this).hasClass('label-modal')) {
            $('#print_button').trigger('click');
        }
    });
    $('button[data-target="#create"]').click(function(){
        var id = $(this).data('order-id'),
            options = $(this).data('label-options');
        $('#order_id').val(id);
        $('#package-type').val(options.package_type);
        if ($(this).data('allowSetOnlyRecipient') === 0) {
            $('#MY_PARCEL_RECIPIENT_ONLY').prop('checked', false).prop('disabled', true);
        } else {
            $('#MY_PARCEL_RECIPIENT_ONLY').prop('disabled', false);
        }
        if (options.only_to_recepient == true && $(this).data('allowSetOnlyRecipient') === 1) {
            $('#MY_PARCEL_RECIPIENT_ONLY').prop('checked', true);
        }
        if (options.age_check == true) {
            $('#MY_PARCEL_AGE_CHECK').prop('checked', true)
        }
        if ($(this).data('allowSetSignature') === 0) {
            $('#MY_PARCEL_SIGNATURE_REQUIRED').prop('checked', false).prop('disabled', true);
        } else {
            $('#MY_PARCEL_SIGNATURE_REQUIRED').prop('disabled', false);
        }
        if (options.signature == true && $(this).data('allowSetSignature') === 1) {
            $('#MY_PARCEL_SIGNATURE_REQUIRED').prop('checked', true);
        }
        if (options.insurance) {
            $('#MY_PARCEL_INSURANCE').prop('checked', true);
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
            data: $('#print-modal :input').serialize(),
            dataType: 'json',
            async: true,
            cache: false,
            headers: { 'cache-control': 'no-cache' }
        }).success(function(jsonData) {
            if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
                window.location.reload();
            } else {
                $('#content > .alert.alert-danger').remove();
                var errorText = '';
                if (typeof jsonData.errors === 'string') {
                    errorText += jsonData.errors;
                } else {
                    $.each(jsonData.errors, function(index, value) {
                        errorText += value + ((index + 1) < jsonData.errors.length ? '<br />' : '');
                    });
                }
                $('#ajax_confirmation').before(
                  '<div class="alert alert-danger">' +
                  '<button type="button" class="close" data-dismiss="alert">×</button>'+errorText+'</div>'
                );
            }
        }).fail(function(error) {
            $('#content > .alert.alert-danger').remove();
            $('#ajax_confirmation').before(
                '<div class="alert alert-danger">' +
                '<button type="button" class="close" data-dismiss="alert">×</button>'+error.responseText+'</div>'
            );
        });
    });

    $('#print-bulk-form').on('submit', function(e) {
        if (!$('input[name="order_ids[]"]', $(this)).length) {
            e.preventDefault();
            $('#ajax_confirmation').before(
              '<div class="alert alert-danger">' +
              '<button type="button" class="close" data-dismiss="alert">×</button>'+no_order_selected_error+'</div>'
            );
            $('#bulk-print').modal('hide');
            $.scrollTo(0);
        }
    });
});



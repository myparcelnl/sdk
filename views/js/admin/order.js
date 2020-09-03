window.addEventListener('load', function() {
    let bulk = document.querySelector('.adminorders .bulk-actions .dropdown-menu:not(.label-dropdown)');
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

        link.innerHTML = '<i class="icon-download"></i> ' + export_labels_text;
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            let idsArray = [];
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(e => {
                if (document.querySelector('button[data-order-id="'+e.value+'"]')){
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
                    order_ids: idsArray
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

    let addBulkExportPrintLabel = function() {
        let link = document.createElement('a');

        link.innerHTML = '<i class="icon-download"></i> ' + export_and_print_label_text;
        if (typeof prompt_for_label_position !== 'undefined' && parseInt(prompt_for_label_position) === 1) {
            link.setAttribute('data-toggle', 'modal');
            link.setAttribute('data-target', '#bulk-export-print');
        }
        link.href = '#';
        link.addEventListener('click', function(e) {
            e.preventDefault();
            $('#export-print-bulk-form').find('input[name="order_ids[]"]').remove();
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(function(e) {
                let $labelIdInput = $('<input type="hidden" name="order_ids[]" value="' + e.value + '">');
                $labelIdInput.prependTo('#bulk-export-print form');
            });
            $('#export-print-bulk-button').attr('disabled', false);
            if (typeof prompt_for_label_position === 'undefined' || parseInt(prompt_for_label_position) !== 1) {
                $('#export-print-bulk-button').trigger('click');
            }
        });

        addBulkOption(link);
    };

    $(document).on('change', '.page-format-radio', function() {
        if ($(this).hasClass('page-format-a4') && $(this).is(':checked')) {
            $(this).closest('form').find('.positions-block').show();
        }
        if ($(this).hasClass('page-format-a6') && $(this).is(':checked')) {
            $(this).closest('form').find('.positions-block').hide();
        }
    });

    $('#bulk-export-print').on('hidden.bs.modal', function (e) {
        if (!$('.error-bulk-action').length) {
            window.location.reload();
        }
    });

    addBulkPrintLabel();
    addBulkRefreshLabel();
    addBulkCreateLabel();
    addBulkExportPrintLabel();
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
        $('#packageType').val(options.package_type);
        if ($(this).data('allowSetOnlyRecipient') === 0) {
            $('#onlyRecipient').prop('checked', false).prop('disabled', true);
        } else {
            $('#onlyRecipient').prop('disabled', false);
        }
        if (options.only_to_recepient == true && $(this).data('allowSetOnlyRecipient') === 1) {
            $('#onlyRecipient').prop('checked', true);
        }
        if (options.age_check == true) {
            $('#ageCheck').prop('checked', true)
        }
        if ($(this).data('allowSetSignature') === 0) {
            $('#signatureRequired').prop('checked', false).prop('disabled', true);
        } else {
            $('#signatureRequired').prop('disabled', false);
        }
        if (options.signature == true && $(this).data('allowSetSignature') === 1) {
            $('#signatureRequired').prop('checked', true);
        }
        if (options.insurance) {
            $('#insurance').prop('checked', true);
        }
    });
    $('#print_button').click(function () {
        $('#print-form').submit();
    });
    $('#print-bulk-button').click(function () {
        $('#print-bulk-form').submit();
    });
    $('#export-print-bulk-button').click(function () {
        $('#export-print-bulk-form').submit();
    });

    $('#export-print-bulk-form').on('submit', function(e) {
        $('#export-print-bulk-button').attr('disabled', 'disabled');
        function checkCookie() {
            var key = 'downloadPdfLabel';

            // To prevent the for loop in the first place assign an empty array
            // in case there are no cookies at all.
            var cookies = document.cookie ? document.cookie.split('; ') : [];
            var jar = {};
            for (var i = 0; i < cookies.length; i++) {
                var parts = cookies[i].split('=');
                var value = parts.slice(1).join('=');
                var foundKey = parts[0];
                jar[foundKey] = value;

                if (key === foundKey) {
                    break;
                }
            }

            return typeof jar[key] !== 'undefined' ? jar[key] : null;
        }
        var intervalLimit = 100;
        var intervalHandle = setInterval(function() {
            if (checkCookie() !== null || intervalLimit <= 0) {
                document.cookie = 'downloadPdfLabel=;expires=Thu, 01 Jan 1970 00:00:01 GMT';
                clearInterval(intervalHandle);
                window.location.reload();
            }
            intervalLimit--;
        }, 1000);
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

    $('#print-bulk-form, #export-print-bulk-form').on('submit', function(e) {
        if (!$('input[name="order_ids[]"]', $(this)).length) {
            e.preventDefault();
            if ($('.error-bulk-action').length) {
                $('.error-bulk-action').remove();
            }
            $('#ajax_confirmation').before(
              '<div class="alert alert-danger error-bulk-action">' +
              '<button type="button" class="close" data-dismiss="alert">×</button>'+no_order_selected_error+'</div>'
            );
            $(this).closest('.modal').modal('hide');
            $.scrollTo(0);
        }
    });

    // Toggle insurance values
    function toggleInsuranceValuesDisplay($el) {
        if (!$el.length || !$el.is(':checked')) {
            $('.insurance-values').hide();
        } else {
            $('.insurance-values').show();
        }
    }
    $('input[name="insurance"]').on('change', function() {
        toggleInsuranceValuesDisplay($(this));
    });
    toggleInsuranceValuesDisplay($('input[name="insurance"]'));

    // Save order label new settings
    $('#submitCreateConcept').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            method: 'POST',
            url: $(this).closest('#myparcel-order-panel').data('url'),
            data: $('.concept-label-wrapper :input').serialize() + '&action=saveConcept&ajax=1&rand=' + Math.random(),
            dataType: 'json',
            async: true,
            cache: false,
            headers: { 'cache-control': 'no-cache' }
        }).success(function(jsonData) {
            if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
                $('#content > .alert.alert-danger').remove();
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
                $.scrollTo(0);
            }
        }).fail(function(error) {
            $('#content > .alert.alert-danger').remove();
            $('#ajax_confirmation').before(
              '<div class="alert alert-danger">' +
              '<button type="button" class="close" data-dismiss="alert">×</button>'+error.responseText+'</div>'
            );
            $.scrollTo(0);
        });
    });

    // Create new shipment label for the current order
    function createLabel($el, reload, callback) {
        $.ajax({
            method: 'POST',
            url: $el.closest('#myparcel-order-panel').data('url'),
            data: $('.concept-label-wrapper :input').serialize() + '&action=createLabel&ajax=1&rand=' + Math.random(),
            dataType: 'json',
            async: true,
            cache: false,
            headers: { 'cache-control': 'no-cache' }
        }).success(function(jsonData) {
            if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
                if (typeof reload !== 'undefined' && reload) {
                    window.location.reload();
                }
                $('#content > .alert.alert-danger').remove();
                if(typeof callback === 'function'){
                    callback.call(this, jsonData);
                }
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
                $.scrollTo(0);
            }
        }).fail(function(error) {
            $('#content > .alert.alert-danger').remove();
            $('#ajax_confirmation').before(
              '<div class="alert alert-danger">' +
              '<button type="button" class="close" data-dismiss="alert">×</button>'+error.responseText+'</div>'
            );
            $.scrollTo(0);
        });
    }
    function printLabel(jsonData) {
        var $form = $('#print_label_form');
        if (typeof jsonData.labelIds !== 'undefined') {
            $.each(jsonData.labelIds, function(index, value) {
                $form.append('<input type="hidden" name="label_id[]" value="'+value+'">');
            });
        }
        $form.submit();
    }
    $('#submitCreateLabel').on('click', function (e) {
        e.preventDefault();
        createLabel($(this), true);
    });

    // Create new shipment label for the current order and print it
    $('#submitCreateLabelPrint').on('click', function (e) {
        e.preventDefault();
    });
    $('#button_print_label').click(function () {
        createLabel($('#submitCreateLabelPrint'), false, printLabel);
    });
});

$(function() {
    let initOrderPageLabelPanel = function() {
        let $panel = $('#myparcel-order-panel');
        if (!$panel.length) {
            return;
        }
        let $kpi = $panel.parent().next('.panel.kpi-container');
        if ($kpi.length) {
            $kpi.insertBefore($panel);
        }
    };

    initOrderPageLabelPanel();
});

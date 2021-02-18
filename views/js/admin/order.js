$(function() {
  let ps177 = false;
  // Bulk actions
  let bulk = $('.adminorders .bulk-actions .dropdown-menu:not(.label-dropdown)');
  if (!bulk.length) {
    let existingButton = $('.adminorders #order_grid #order_grid_bulk_action_change_order_status');
    if (!existingButton.length) {
      return;
    }
    bulk = existingButton.closest('.dropdown-menu');
    ps177 = true;
  }


  let addBulkOption = function (link) {
    if (ps177) {
      return;
    }
    let item = $('<li/>');
    item.append(link);
    bulk.append(item);
  };

  let addBulkCreateLabel = function () {
    addBulkButton(
      'order_grid_bulk_action_create_label',
      (typeof export_labels_text !== 'undefined' ? export_labels_text : ''),
      no_order_selected_error,
      create_labels_bulk_route
    );
  };

  let addBulkRefreshLabel = function () {
    addBulkButton(
      'order_grid_bulk_action_refresh_label',
      (typeof refresh_labels_text !== 'undefined' ? refresh_labels_text : ''),
      no_order_selected_error,
      refresh_labels_bulk_route
    );
  };

  let addBulkPrintLabel = function () {
    addModalBulkButton(
      'order_grid_bulk_action_print_label',
      (typeof print_labels_text !== 'undefined' ? print_labels_text : ''),
      'bulk-print',
      'print-bulk-form',
      'print-bulk-button'
    );
  };

  let addBulkExportPrintLabel = function () {
    addModalBulkButton(
      'order_grid_bulk_action_create_print_label',
      (typeof export_and_print_label_text !== 'undefined' ? export_and_print_label_text : ''),
      'bulk-export-print',
      'export-print-bulk-form',
      'export-print-bulk-button'
    );
  };

  let addModalBulkButton = function(buttonId, labelText, modalId, formId, formButtonId) {
    let link = getBulkButton(buttonId, labelText, true, modalId);

    link.on('click', function (e) {
      e.preventDefault();
      let $form = $('#' + formId);
      $form.find('input[name="order_ids[]"]').remove();
      $('input[name="orderBox[]"]:checked, .js-bulk-action-checkbox[name="order_orders_bulk[]"]:checked').each(function(e) {
        let idOrder = parseInt($(this).val());
        if (isNaN(idOrder) || idOrder <= 0) {
          return true; // skip this element
        }
        if (!$('button[data-order-id="' + idOrder + '"]').length) {
          return true; // skip this element
        }
        let $labelIdInput = $('<input type="hidden" name="order_ids[]" value="' + idOrder + '">');
        $labelIdInput.prependTo($form);
      });
      let $formButton = $('#' + formButtonId);
      $formButton.attr('disabled', false);
      if (link.data('toggle') !== 'modal' && link.data('modalId') !== modalId) {
        $formButton.trigger('click');
      }
    });

    addBulkOption(link);
  }

  let addBulkButton = function(buttonId, labelText, errorText, route) {
    let link = getBulkButton(buttonId, labelText, false);

    link.on('click', function (e) {
      e.preventDefault();
      let idsArray = [];
      $('input[name="orderBox[]"]:checked, .js-bulk-action-checkbox[name="order_orders_bulk[]"]:checked').each(function(e) {
        let idOrder = parseInt($(this).val());
        if (isNaN(idOrder) || idOrder <= 0) {
          return true; // skip this element
        }
        if ($('button[data-order-id="' + idOrder + '"]').length) {
          idsArray.push(idOrder);
        }
      });
      if (!idsArray.length) {
        $('#ajax_confirmation').before(
          '<div class="alert alert-danger">' +
          '<button type="button" class="close" data-dismiss="alert">×</button>' + errorText + '</div>'
        );
        $('html').animate({scrollTop: 0}, 400);
        return;
      }
      $.ajax({
        method: "POST",
        url: route,
        data: {
          order_ids: idsArray
        }
      }).done((result) => {
        window.location.reload();
      }).fail((error) => {
        $('#ajax_confirmation').before(
          '<div class="alert alert-danger">' +
          '<button type="button" class="close" data-dismiss="alert">×</button>' + error.responseText + '</div>'
        )
      });
    });

    addBulkOption(link);
  }

  let getBulkButton = function(buttonId, labelText, hasModal, modalId) {
    let link;
    if (ps177) {
      link = $('#' + buttonId);
    } else {
      let linkProps = {
        href: '#',
        title: ''
      };
      if (hasModal && typeof prompt_for_label_position !== 'undefined' && parseInt(prompt_for_label_position) === 1) {
        linkProps['data-toggle'] = 'modal';
        linkProps['data-target'] = '#' + modalId;
      }
      link = $('<a/>', linkProps);
      link.html('<i class="icon-download"></i> ' + labelText);
    }

    return link;
  }

  $(document).on('change', '.page-format-radio', function () {
    if ($(this).hasClass('page-format-a4') && $(this).is(':checked')) {
      $(this).closest('form').find('.positions-block').show();
    }
    if ($(this).hasClass('page-format-a6') && $(this).is(':checked')) {
      $(this).closest('form').find('.positions-block').hide();
    }
  });

  // $('#bulk-export-print').on('hidden.bs.modal', function (e) {
  //   if (!$('.error-bulk-action').length) {
  //     window.location.reload();
  //   }
  // });

  addBulkPrintLabel();
  addBulkRefreshLabel();
  addBulkCreateLabel();
  addBulkExportPrintLabel();
});

$(function() {
  // General actions
  function displayOrderAjaxErrors(jsonData) {
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
    $('html').animate({ scrollTop: 0 }, 400);
  }
  function displayOrderAjaxFailErrors(error) {
    if (typeof error.responseJSON !== 'undefined' && typeof error.responseJSON.hasError !== 'undefined') {
      displayOrderAjaxErrors(error.responseJSON);
      return;
    }
    $('#content > .alert.alert-danger').remove();
    $('#ajax_confirmation').before(
      '<div class="alert alert-danger">' +
      '<button type="button" class="close" data-dismiss="alert">×</button>'+error.responseText+'</div>'
    );
    $('html').animate({ scrollTop: 0 }, 400);
  }
  function checkDownloadPdfCookie() {
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
    $('#packageFormat').val(options.package_format);
    if ($(this).data('allowSetOnlyRecipient') === 0) {
      $('#onlyRecipient').prop('checked', false).prop('disabled', true);
    } else {
      $('#onlyRecipient').prop('disabled', false);
    }
    if (options.only_to_recipient == true && $(this).data('allowSetOnlyRecipient') === 1) {
      $('#onlyRecipient').prop('checked', true);
    }
    if (options.age_check == true) {
      $('#ageCheck').prop('checked', true)
    }
    if (options.return_undelivered == true) {
      $('#returnUndelivered').prop('checked', true)
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
    var intervalLimit = 100;
    var intervalHandle = setInterval(function() {
      if (checkDownloadPdfCookie() !== null || intervalLimit <= 0) {
        document.cookie = 'downloadPdfLabel=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;samesite=strict';
        clearInterval(intervalHandle);
        $('#print').modal('hide');
      }
      intervalLimit--;
    }, 1000);
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
    var intervalLimit = 100;
    var intervalHandle = setInterval(function() {
      var cookieStatus = checkDownloadPdfCookie();
      if (cookieStatus !== null || intervalLimit <= 0) {
        document.cookie = 'downloadPdfLabel=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/;samesite=strict';
        clearInterval(intervalHandle);
        if (cookieStatus !== null) {
          window.location.reload();
        }
      }
      intervalLimit--;
    }, 1000);
  });

  $('#add').click(function () {
    $.ajax({
      method: "POST",
      url: create_label_action + '&rand=' + Math.random(),
      data: $('#print-modal :input').serialize(),
      dataType: 'json',
      async: true,
      cache: false,
      headers: { 'cache-control': 'no-cache' }
    }).done(function(jsonData) {
      if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
        window.location.reload();
      } else {
        displayOrderAjaxErrors(jsonData)
      }
    }).fail(function(error) {
      displayOrderAjaxFailErrors(error);
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
      $('html').animate({ scrollTop: 0 }, 400);
    }
  });

  // Toggle insurance values
  function toggleInsuranceValuesDisplay($el) {
    var $parent = $el.closest('.label-delivery-options');
    if (!$el.length || !$el.is(':checked')) {
      $('.insurance-values', $parent).hide();
    } else {
      $('.insurance-values', $parent).show();
    }
  }
  $('input[name="insurance"]').on('change', function() {
    toggleInsuranceValuesDisplay($(this));
  });
  $('input[name="insurance"]').each(function() {
    toggleInsuranceValuesDisplay($(this));
  });
  $('input[name="insurance-amount-custom-value"]').on('focus', function() {
    let $parent = $(this).closest('.insurance-amount-custom');
    if ($parent.length) {
      $parent.find('input[type="radio"]').prop('checked', 'checked');
    }
    $parent = $(this).closest('.return-insurance-amount-custom');
    if ($parent.length) {
      $parent.find('input[type="radio"]').prop('checked', 'checked');
    }
  });

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
    }).done(function(jsonData) {
      if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
        $('#content > .alert.alert-danger').remove();
      } else {
        displayOrderAjaxErrors(jsonData)
      }
    }).fail(function(error) {
      displayOrderAjaxFailErrors(error);
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
    }).done(function(jsonData) {
      if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
        if (typeof reload !== 'undefined' && reload) {
          window.location.reload();
        }
        $('#content > .alert.alert-danger').remove();
        if (typeof jsonData.labelsHtml !== 'undefined') {
          $('.shipment-labels-wrapper .table-responsive').html(jsonData.labelsHtml);
        }
        if(typeof callback === 'function'){
          callback.call(this, jsonData);
        }
      } else {
        displayOrderAjaxErrors(jsonData)
      }
      toggleProcessIcon($el);
    }).fail(function(error) {
      displayOrderAjaxFailErrors(error);
      toggleProcessIcon($el);
    });
  }

  function toggleProcessIcon($el) {
    if ($el.data('iconPlus') === 1) {
      $('i', $el).removeClass('icon-refresh icon-spin icon-fw').addClass('icon-plus');
    }
  }

  function printLabel(jsonData) {
    var $form = $('#print_label_form');
    if (typeof jsonData !== 'undefined' && jsonData && typeof jsonData.labelIds !== 'undefined') {
      $.each(jsonData.labelIds, function(index, value) {
        $form.append('<input type="hidden" name="label_id[]" value="'+value+'">');
      });
    }
    $form.submit();
    $('#printLabelModal').modal('hide');
    toggleLabelsBulkButtons();
  }
  function toggleLabelsBulkButtons() {
    var $container = $('.shipment-labels-wrapper');
    var labelCount = $('tbody > tr.tr-label-item', $container).length;
    if (labelCount) {
      $('tbody > tr.tr-empty-notice', $container).addClass('hidden d-none');
      $('.shipment-labels-bulk-actions > .btn.dropdown-toggle', $container).attr('disabled', false);
      return;
    }
    $('tbody > tr.tr-empty-notice', $container).removeClass('hidden d-none');
    $('.shipment-labels-bulk-actions > .btn.dropdown-toggle', $container).attr('disabled', true);
  }
  $('#submitCreateLabel').on('click', function (e) {
    e.preventDefault();
    $(this).data('iconPlus', 1);
    $('i', $(this)).removeClass('icon-plus').addClass('icon-refresh icon-spin icon-fw');
    createLabel($(this), false, toggleLabelsBulkButtons);
  });

  // Create new shipment label for the current order and print it
  $('#submitCreateLabelPrint').on('click', function (e) {
    e.preventDefault();
    var $button = $('#button_print_label');
    if ($(this).data('toggle') === 'no-modal' && $button.length) {
      $button.trigger('click');
    }
  });
  $('#button_print_label').click(function () {
    $(this).prepend('<i class="icon-refresh icon-spin icon-fw"></i>');
    if ($('#print_label_form').find('.bulk-label-id').length) {
      printLabel(null);
    } else {
      createLabel($('#submitCreateLabelPrint'), false, printLabel);
    }
  });

  $('.shipment-labels-wrapper')
    .on('click', '.order-label-action-delete', function(e) {
      e.preventDefault();
      var $form = $(this).closest('form');
      var $tr = $(this).closest('tr');
      $.ajax({
        method: 'POST',
        url: $form.prop('action'),
        data: 'id_order_label=' + $tr.data('id') + '&action=deleteLabel&ajax=1&rand=' + Math.random(),
        dataType: 'json',
        async: true,
        cache: false,
        headers: { 'cache-control': 'no-cache' }
      }).done(function(jsonData) {
        $('#content > .alert.alert-danger').remove();
        if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
          $tr.remove();
          toggleLabelsBulkButtons();
        } else {
          displayOrderAjaxErrors(jsonData);
        }
      }).fail(function(error) {
        displayOrderAjaxFailErrors(error);
      });
    })
    .on('click', '.order-label-action-print', function(e) {
      e.preventDefault();
      var $tr = $(this).closest('tr');
      if (!$(this).hasClass('label-modal')) {
        var jsonData = {labelIds: [$tr.data('labelId')]};
        printLabel(jsonData);
      } else {
        var $printForm = $('#print_label_form');
        var labelId = $tr.data('labelId');
        $('.bulk-label-id', $printForm).remove();
        $printForm.append('<input type="hidden" name="label_id[]" value="' + labelId + '" class="bulk-label-id">');
      }
    })
    .on('click', '.order-label-action-refresh', function(e) {
      e.preventDefault();
      var $form = $(this).closest('form');
      var $tr = $(this).closest('tr');
      $.ajax({
        method: 'POST',
        url: $form.prop('action'),
        data: 'id_label=' + $tr.data('labelId') + '&action=refreshLabel&ajax=1&rand=' + Math.random(),
        dataType: 'json',
        async: true,
        cache: false,
        headers: { 'cache-control': 'no-cache' }
      }).done(function(jsonData) {
        $('#content > .alert.alert-danger').remove();
        if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
          if (typeof jsonData.labelsHtml !== 'undefined') {
            $('.shipment-labels-wrapper .table-responsive').html(jsonData.labelsHtml);
          }
        } else {
          displayOrderAjaxErrors(jsonData);
        }
      }).fail(function(error) {
        displayOrderAjaxFailErrors(error);
      });
    })
    .on('click', '.order-label-action-return', function(e) {
      e.preventDefault();
      var $tr = $(this).closest('tr');
      $('.custom-label-return-description input[name="label_description"]').val($tr.data('return'));
      $('#return_label_form input[name="id_order_label"]').val($tr.data('id'));
      $('#labelReturnModal input[name="insurance"]').each(function() {
        toggleInsuranceValuesDisplay($(this));
      });
      $('#labelReturnModal').modal('show');
    })
    .on('click', '.bulk-actions a.bulk-actions-links', function(e) {
      e.preventDefault();
      var $el = $(this);
      var $form = $el.closest('form');
      if (parseInt($el.data('ajax')) === 0) {
        var $printForm = $('#print_label_form');
        $('.bulk-label-id', $printForm).remove();
        $('input[name="labelBox[]"]:checked', $form).each(function() {
          var labelId = $(this).closest('tr').data('labelId');
          $printForm.append('<input type="hidden" name="label_id[]" value="' + labelId + '" class="bulk-label-id">');
        });
        if ($('input[name="labelBox[]"]:checked', $form).length) {
          if (typeof prompt_for_label_position !== 'undefined' && parseInt(prompt_for_label_position) === 1) {
            $('#printLabelModal').modal('show');
          } else {
            $('#button_print_label').trigger('click');
          }
        }
        return;
      }
      $.ajax({
        method: 'POST',
        url: $form.prop('action'),
        data: $(':input', $form).serialize()
          + '&action=' + $el.data('action')
          + '&ajax=' + $el.data('ajax')
          + '&rand=' + Math.random(),
        dataType: 'json',
        async: true,
        cache: false,
        headers: { 'cache-control': 'no-cache' }
      }).done(function(jsonData) {
        $('#content > .alert.alert-danger').remove();
        if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
          if (typeof jsonData.labelsHtml !== 'undefined') {
            $('.shipment-labels-wrapper .table-responsive').html(jsonData.labelsHtml);
          }
        } else {
          displayOrderAjaxErrors(jsonData);
        }
      }).fail(function(error) {
        displayOrderAjaxFailErrors(error);
      });
    });

  $('#buttonLabelReturn').on('click', function(e) {
    e.preventDefault();

    var $el = $(this);
    var $form = $('#return_label_form');

    $.ajax({
      method: 'POST',
      url: $form.prop('action'),
      data: $(':input', $form).serialize()
        + '&action=' + $el.data('action')
        + '&ajax=' + $el.data('ajax')
        + '&rand=' + Math.random(),
      dataType: 'json',
      async: true,
      cache: false,
      headers: { 'cache-control': 'no-cache' }
    }).done(function(jsonData) {
      $('#content > .alert.alert-danger').remove();
      if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
        if (typeof jsonData.labelsHtml !== 'undefined') {
          $('.shipment-labels-wrapper .table-responsive').html(jsonData.labelsHtml);
        }
      } else {
        displayOrderAjaxErrors(jsonData);
      }
      $('#labelReturnModal').modal('hide');
    }).fail(function(error) {
      displayOrderAjaxFailErrors(error);
      $('#labelReturnModal').modal('hide');
    });
  });

  $('#printLabelModal').on('hidden.bs.modal', function () {
    $('[name="label_id[]"]', $(this)).remove();
  }).on('show.bs.modal', function() {
    $('i.icon-refresh', $(this)).remove();
  });

  let initializeMyParcelForm = function () {
    var $wrapper = $('#deliveryDateUpdateWrapper');
    if (!$wrapper.length) {
      return;
    }

    let carrierId = $('input[name="id_carrier"]', $wrapper).val();
    if (!(parseInt(carrierId) > 0)) {
      return;
    }

    $.ajax({
      url: delivery_settings_route + '&id_carrier=' + carrierId,
      dataType: "json",
      success: function (data) {
        window.MyParcelConfig = data;
        let $form = $('.myparcel-delivery-options');
        if ($form) {
          $form.remove();
        }
        $wrapper.append('<div id="myparcel-delivery-options"></div>');
        document.dispatchEvent(new Event('myparcel_render_delivery_options'));
      }
    });
  }
  let updateMypaInput = function(dataObj) {
    let $input = $('#mypa-input');
    if (!$input.length) {
      $input = $('<input type="hidden" class="mypa-post-nl-data" id="mypa-input" name="myparcel-delivery-options" />');
      let $form = $('#deliveryDateUpdateWrapper .hidden-input-fields-form');
      if ($form.length) {
        $form.append($input);
      }
    }

    let dataString = JSON.stringify(dataObj)

    $input.val(dataString);
  }

  $('.delivery-options-span').on('click', function(e) {
    e.preventDefault();
  });
  $('#deliveryDateModal').on('shown.bs.modal', function (e) {
    initializeMyParcelForm();
  });
  document.addEventListener(
    'myparcel_updated_delivery_options',
    (event) => updateMypaInput(event.detail)
  );
  // Save order label new settings
  $('#buttonDeliveryDateUpdate').on('click', function (e) {
    e.preventDefault();
    var $modal = $(this).closest('.modal-content');
    $.ajax({
      method: 'POST',
      url: $(this).data('url'),
      data: $('form.hidden-input-fields-form :input', $modal).serialize() + '&action=updateDeliveryOptions&ajax=1&rand='
        + Math.random(),
      dataType: 'json',
      async: true,
      cache: false,
      headers: { 'cache-control': 'no-cache' }
    }).done(function(jsonData) {
      if (typeof jsonData.hasError === 'undefined' || !jsonData.hasError) {
        if (typeof jsonData.labelConceptHtml !== 'undefined') {
          $('.concept-label-wrapper').html(jsonData.labelConceptHtml);
          toggleInsuranceValuesDisplay($('input[name="insurance"]'));
        }
      } else {
        displayOrderAjaxErrors(jsonData)
      }
      $('#deliveryDateModal').modal('hide');
    }).fail(function(error) {
      displayOrderAjaxFailErrors(error);
    });
  });

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

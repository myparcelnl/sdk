document.addEventListener('DOMContentLoaded', function() {

  const insuranceCheckboxSelector = '.myparcel-insurance-checkbox';
  let toggleInsuranceAdditional = function() {
    const insuranceAdditionalActiveClassname = 'insurance-active';
    let $insuranceCheckbox = $(insuranceCheckboxSelector).first();
    let $insuranceAdditional = $('.insurance-additional-container');
    let isChecked = $insuranceCheckbox.is(':checked');

    if (isChecked) {
      $insuranceAdditional.addClass(insuranceAdditionalActiveClassname);
    } else {
      $insuranceAdditional.removeClass(insuranceAdditionalActiveClassname);
    }
  };
  $(document).on('change', insuranceCheckboxSelector, function() {
    toggleInsuranceAdditional();
  });
  toggleInsuranceAdditional();

  const insuranceHigherAmountSelector = '#myparcel-insurance-higher-amount';
  let changeInsuranceHigherAmount = function() {
    const amountStep = 500;
    let $insuranceHigherAmountInput = $(insuranceHigherAmountSelector);
    let currentValue = parseFloat($insuranceHigherAmountInput.val());

    let steps = Math.ceil(currentValue / amountStep);
    let newValue = amountStep * steps;
    if (newValue > 5000) {
      newValue = 5000;
    }
    $insuranceHigherAmountInput.val(newValue);
  };
  $(document).on('change', insuranceHigherAmountSelector, function() {
    changeInsuranceHigherAmount();
  });
  changeInsuranceHigherAmount();

  if ($('#MYPARCELBE_LABEL_SIZE').val() == 'a6') {
    $('.label_position').hide();
  }
  $('#MYPARCELBE_LABEL_SIZE').change(function() {
    if ($(this).val() == 'a6') {
      $('.label_position').hide();
    } else {
      $('.label_position').show();
    }
  });
  $(document).on('click', '.label-description-variables code', function() {
    var $input = $(this).closest('.col-lg-9').find('input[type="text"]');
    if ($input.length) {
      $input.val($input.val() + ' ' + $(this).html());
    }
  });

  if ($('body').hasClass('adminmodules') && $('#configuration_form').length) {
    $('.toggle-parent-field input[type="radio"]').on('change', function() {
      toggleFieldsVisibility($(this));
    });
    $('.toggle-parent-field input[type="radio"]:checked').each(function() {
      toggleFieldsVisibility($(this));
    });
  }
  function toggleFieldsVisibility($el) {
    var fieldName = $el.prop('name');
    if ($el.prop('value') === '1') {
      $('.toggle-child-field.' + fieldName).show();
    } else {
      $('.toggle-child-field.' + fieldName).hide();
    }
  }
}, false);

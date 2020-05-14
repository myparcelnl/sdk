document.addEventListener('DOMContentLoaded', function() {

  const insuranceCheckboxSelector = '#myparcel-insurance-checkbox';
  let toggleInsuranceAdditional = function() {
    const insuranceAdditionalActiveClassname = 'insurance-active';
    let $insuranceCheckbox = $(insuranceCheckboxSelector);
    let $insuranceAdditional = $('.insurance-additional-container');
    let isChecked = $insuranceCheckbox.is(':checked');

    if(isChecked) {
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
}, false);

document.addEventListener("DOMContentLoaded", () => {
    $('.addLabel').click(function(){
        $('.js-bulk-action-checkbox').prop('checked', false);
        $(this).closest('tr').find('.js-bulk-action-checkbox').prop('checked', true);
    });

    $('.printLabel').click(function () {
        var label_id = $(this).data('label_id');
        $('#label_id').val(label_id);
    });

    $('.bulk-print').click(function () {
        $('#bulk-print-modal').modal('hide');
    });

    $('.single-print').click(function(){
        $('#print-form').submit();
        $('#print-modal').modal('hide');
    });

    $('input[value='+default_label_size+']').prop('checked', true);
    $('input[value='+default_label_position+']').prop('checked', true);
    if (prompt_for_label_position == 0) {
        $('.positions-block').remove();
    }
});
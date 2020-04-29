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
            ids = [];
            document.querySelectorAll('input[name="orderBox[]"]:checked').forEach(e => {
                ids.push(e.value);
            });

            $.ajax({
                method: "POST",
                url: create_labels_bulk_route,
                data: {
                    order_ids: ids
                }
            }).done((result) => {
                window.location.reload();
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
                window.location.reload();
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
            package_type = $(this).data('package-type');
        $('#order_id').val(id);
        $('#package-type').val(package_type);
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
            url: '{{$action}}', //TODO pass action param
            data: $('#print-modal :input').serialize()
        }).done((result) => {
            window.location.reload();
        }).fail(() => {

        });
    });
});



window.addEventListener('load', function() {
    let bulk = document.querySelector('.adminorders .bulk-actions .dropdown-menu')
    if (!bulk) {
        return
    }

    // Build item
    let item = document.createElement('li'),
        link = document.createElement('a')

    link.innerHTML = '<i class="icon-download"></i> ' + "Create label"
    link.href = '#'
    link.addEventListener('click', function(e) {
        e.preventDefault();
        ids = []
        document.querySelectorAll('input[name="orderBox[]"]:checked').forEach (e => {
            ids.push(e.value)
        })

        $.ajax({
            method: "POST",
            url: create_labels_bulk_route,
            data: {
                order_orders_bulk : ids
            }
        }).done((result) => {
            window.location.reload();
        }).fail(() => {

        });

        //window.location = "EXport label link" + '&ids=' + ids.join()
    })
    item.appendChild(link)

    // Add item
    bulk.appendChild(item)


    let printItem = document.createElement('li'),
        printLink = document.createElement('a')

    link.innerHTML = '<i class="icon-download"></i> ' + "Create label"
    link.href = '#'
    link.addEventListener('click', function(e) {
        e.preventDefault();
        ids = []
        document.querySelectorAll('input[name="orderBox[]"]:checked').forEach (e => {
            ids.push(e.value)
        })

        $.ajax({
            method: "POST",
            url: create_labels_bulk_route,
            data: {
                order_orders_bulk : ids
            }
        }).done((result) => {
            window.location.reload();
        }).fail(() => {

        });

        //window.location = "EXport label link" + '&ids=' + ids.join()
    })
    item.appendChild(link)

    // Add item
    bulk.appendChild(item)
});

document.addEventListener("DOMContentLoaded", () => {
    $('button[data-target="#print"]').click(function(){
        var id = $(this).data('label-id');
        $('#id_label').val(id);
    });
    $('button[data-target="#create"]').click(function(){
        var id = $(this).data('order-id');
        $('#order_id').val(id);
    });
    $('#print_button').click(function () {
        $('#print-form').submit();
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



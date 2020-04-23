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
    link.addEventListener('click', function() {
        ids = []
        document.querySelectorAll('input[name="orderBox[]"]:checked').forEach (e => {
            ids.push(e.value)
        })

        $.ajax({
            method: "POST",
            url: '{{$action}}',
            data: $('#print-modal :input').serialize()
        }).done((result) => {
            window.location.reload();
        }).fail(() => {

        });

        window.location = "EXport label link" + '&ids=' + ids.join()
    })
    item.appendChild(link)

    // Add item
    bulk.appendChild(item)
})











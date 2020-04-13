window.addEventListener('load', function() {
    let bulk = document.querySelector('.adminorders .bulk-actions .dropdown-menu')
    if (!bulk) {
        return
    }

    // Build item
    let item = document.createElement('li'),
        link = document.createElement('a')

    link.innerHTML = '<i class="icon-download"></i> ' + MYPARCEL_LANG['Create Label']
    link.href = '#'
    link.addEventListener('click', function() {
        ids = []
        document.querySelectorAll('input[name="orderBox[]"]:checked').forEach (e => {
            ids.push(e.value)
        })
        window.location = DHLBATCHEXPORT_LINK + '&ids=' + ids.join()
    })
    item.appendChild(link)

    // Add item
    bulk.appendChild(item)
})

function alert_success (text) {
    notif({
        msg: '<b>Sukses!</b> ' + text,
        type: 'success',
        position: 'right',
        fade: true
    })
}

function alert_error (text) {
    notif({
        msg: '<b>Oops!</b> ' + text,
        type: 'error',
        position: 'right',
        fade: true
    })
}

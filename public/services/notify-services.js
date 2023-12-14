(function($) {
    "use strict";

    $(document).ready(function() {

    });


    $(window).on('load',async function() {
        checkHeaderNotifyCount();
    });


})(window.jQuery);

/*Listen Status Change*/

Pusher.logToConsole = true;

var pusher = new Pusher('c52432e0f85707bee8d3', {
    cluster: 'eu'
});

var channel = pusher.subscribe('status-channel');
channel.bind('App\\Events\\StatusChange', function(data) {
    handleStatusChangeEvent(data)
});

async function handleStatusChangeEvent(data) {
    let id = data.id;
    let title = data.title;
    let message = data.message;
    let receiver_id = data.receiver_id;
    let user_id = localStorage.getItem('userId');
    if (receiver_id == user_id) {
        showNotify(id, title, message);
    }
}

/*End Listen Status Change*/




function showNotify(notify_id, title, message) {
    let notify = '  <div id="notify-'+ notify_id +'" class="toast" data-autohide="false">\n' +
        '               <div class="toast-header">\n' +
        '                   <i class="far fa-bell text-muted me-2"></i>\n' +
        '                   <strong class="me-auto">'+ title +'</strong>\n' +
        // '                   <small>11 mins ago</small>\n' +
        '                   <button type="button" class="btn-close" data-bs-dismiss="toast"></button>\n' +
        '               </div>\n' +
        '               <div class="toast-body">\n' +
        '                   '+ message +'\n' +
        '               </div>\n' +
        '           </div>\n';
    $('.toasts-container').append(notify);

    let $notifyElement = $('#notify-'+ notify_id);
    $notifyElement.toast({
        autohide: false,
        delay: 3000
    }).toast('show');
    $notifyElement.on('hidden.bs.toast', function () {
        markAsReadSingleNotify(notify_id);
    });
}

async function markAsReadSingleNotify(notify_id){
    await serviceGetReadNotifyById(notify_id);
    checkHeaderNotifyCount();
}

async function markAsReadAllNotify(){
    let user_id = localStorage.getItem('userId');

    await serviceGetReadAllNotifyByUserId(user_id);
    checkHeaderNotifyCount();
}

async function checkHeaderNotifyCount(){
    let user_id = localStorage.getItem('userId');

    let data = await serviceGetNotReadNotifyCountByUserId(user_id);
    if (data.count == 0){
        $('#header-notify .badge').addClass('d-none');
    }else{
        $('#header-notify .badge').text(data.count);
        $('#header-notify .badge').removeClass('d-none');
    }
}

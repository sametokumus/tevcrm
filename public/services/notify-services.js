(function($) {
    "use strict";

    $(document).ready(function() {

    });


    $(window).on('load',async function() {

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

/*Listen Status Change*/

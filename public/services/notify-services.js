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
    cluster: 'eu',
    channelAuthorization: {
        endpoint: "/pusher_auth.php",
        headers: { "X-CSRF-Token": document.head.querySelector('meta[name="csrf-token"]').content },
    }
});

var channel = pusher.subscribe('presence-status-channel');

channel.bind('pusher:subscription_succeeded', function (members) {
    members.each(function (member) {
        handleStatusChangeEvent(member.info);
    });
});

channel.bind('App\\Events\\StatusChange', function (data) {
    handleStatusChangeEvent(data);
});

async function handleStatusChangeEvent(data) {
    let id = data.id;
    let title = data.title;
    let message = data.message;
    showNotify(id, title, message);
}

/*Listen Status Change*/

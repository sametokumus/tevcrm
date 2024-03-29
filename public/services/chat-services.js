(function($) {
    "use strict";

    $(document).ready(function() {

        $('#chat-form').submit(function (e){
            e.preventDefault();
            sendPublicChatMessage();
        });

        $('.app-theme-toggle-btn').click(function (){
            if ($('.app-theme-panel').hasClass('active')){
                localStorage.setItem('chatCount', 0);
                $('.app-theme-toggle-btn .badge').addClass('d-none');
                $('.app-theme-toggle-btn .badge').text('');
                $('#chat-icon').removeClass('bi-envelope').addClass('bi-arrow-right');
            }else{
                $('#chat-icon').removeClass('bi-arrow-right').addClass('bi-envelope');
            }
        });

        if ($('.app-theme-panel').hasClass('active')){
            $('#chat-icon').removeClass('bi-envelope').addClass('bi-arrow-right');
        }else{
            $('#chat-icon').removeClass('bi-arrow-right').addClass('bi-envelope');
        }

        $('#chat-panel-close').click(function (){
            $('#chat-icon').removeClass('bi-arrow-right').addClass('bi-envelope');
        });

    });


    $(window).on('load',async function() {
        getPublicChats();
    });


})(window.jQuery);

let chatPage = 1;
let last_day = '';
let last_user = '';
async function getPublicChats(){
    let user_id = await localStorage.getItem('userId');

    let chatCount = localStorage.getItem('chatCount');
    if (chatCount != null && chatCount != 0) {
        $('.app-theme-toggle-btn .badge').removeClass('d-none');
        $('.app-theme-toggle-btn .badge').text(chatCount);
    }


        let data = await serviceGetCompanyChatMessages(chatPage);
    let messages = data.messages.data.reverse();
    let message_panel = '';

    $.each(messages, function(i, message){

        let reply = '';
        let user_image = '<div class="widget-chat-media" style="background-image: url(\''+ message.sender.profile_photo +'\')"></div>\n';
        if (message.sender.profile_photo == null){
            user_image = '<div class="widget-chat-media" style="background-image: url(\'img/user/null-profile-picture.png\')"></div>\n';
        }
        if (message.sender_id == user_id){
            reply = 'reply';
            user_image = '';
        }

        message_panel += '';
        if (last_day != formatDateASC(message.created_at, '-')){
            if (last_user != ''){
                message_panel += '      </div>\n' +
                    '               </div>\n';
            }
            message_panel += '<div class="widget-chat-date">'+ formatDateASC(message.created_at, '-') +'</div>\n';
            last_user = '';
        }

        if (last_user != message.sender_id){
            if (last_user != ''){
                message_panel += '      </div>\n' +
                    '               </div>\n';
                message_panel += '  <div class="widget-chat-item '+ reply +'">\n' +
                    '                   '+ user_image +'\n' +
                    '                   <div class="widget-chat-content">\n';
            }else{
                message_panel += '  <div class="widget-chat-item '+ reply +'">\n' +
                    '                   '+ user_image +'\n' +
                    '                   <div class="widget-chat-content">\n';
            }

            if (user_id != message.sender_id) {
                message_panel += '<div class="widget-chat-name">' + message.sender.name + ' ' + message.sender.surname + '</div>\n';
            }

            message_panel += '<div class="widget-chat-message">\n' +
                '                 '+ message.message +'\n' +
                '                 <div class="widget-chat-status">'+ formatTime(message.created_at) +'</div>\n' +
                '             </div>\n';

        }else{
            message_panel += '<div class="widget-chat-message">\n' +
                '                 '+ message.message +'\n' +
                '                 <div class="widget-chat-status">'+ formatTime(message.created_at) +'</div>\n' +
                '             </div>\n';
        }


        last_user = message.sender_id;
        last_day = formatDateASC(message.created_at, '-');
    });


    $('.app-theme-panel .widget-chat').append(message_panel);

    let scrollableDiv = document.getElementById('chat-body');
    scrollableDiv.scrollTop = scrollableDiv.scrollHeight;

}

async function sendPublicChatMessage (){
    const messageInput = document.getElementById('chat-message-text');
    const message = messageInput.value.trim();

    if (message != ''){

        let formData = JSON.stringify({
            "message": message,
            "user_id": localStorage.getItem('userId')
        });
        let returned = servicePostCompanyChatMessage(formData);
        if (!returned){
            showAlert('Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyiniz!');
        }else{
            document.getElementById('chat-message-text').value = '';
        }

    }else{
        showAlert('Boş mesaj gönderemezsiniz!');
    }
}

/*Listen Chat Message*/

Pusher.logToConsole = true;

var pusher = new Pusher('c52432e0f85707bee8d3', {
    cluster: 'eu'
});

var channel = pusher.subscribe('company-chat-channel');
channel.bind('App\\Events\\CompanyChat', function(data) {
    handleSendMessageEvent(data)
});

async function handleSendMessageEvent(data) {
    playSound();

    let message = data.message;
    let sender = data.user;
    let message_panel = '';
    let user_id = await localStorage.getItem('userId');
    let reply = '';
    let user_image = '<div class="widget-chat-media" style="background-image: url(\''+ sender.profile_photo +'\')"></div>\n';
    if (sender.profile_photo == null){
        user_image = '<div class="widget-chat-media" style="background-image: url(\'img/user/null-profile-picture.png\')"></div>\n';
    }
    if (message.sender_id == user_id){
        reply = 'reply';
        user_image = '';
    }

    message_panel += '';
    message_panel += '  <div class="widget-chat-item '+ reply +'">\n' +
        '                   '+ user_image +'\n' +
        '                   <div class="widget-chat-content">\n';

    if (message.sender_id != user_id && last_user != message.sender_id){
        message_panel += '      <div class="widget-chat-name">'+ sender.name + ' ' + sender.surname +'</div>\n';
    }

    message_panel += '          <div class="widget-chat-message">\n' +
        '                           '+ message.message +'\n' +
        '                           <div class="widget-chat-status">'+ formatTime(message.created_at) +'</div>\n' +
        '                       </div>\n';
    message_panel += '      </div>\n' +
        '               </div>\n';



    $('.app-theme-panel .widget-chat').append(message_panel);

    let scrollableDiv = document.getElementById('chat-body');
    scrollableDiv.scrollTop = scrollableDiv.scrollHeight;

    if ($('#public-chat-panel').hasClass('active') != true){
        let title = sender.name + ' ' + sender.surname;
        let message_text = message.message;
        let id = message.message_id;
        showChatNotify(id, title, message_text);

        let chatCount = localStorage.getItem('chatCount');
        if (chatCount == null){
            chatCount = 1;
        }else{
            chatCount = parseInt(chatCount) + 1;
        }
        localStorage.setItem('chatCount', chatCount);
        if ($('.app-theme-toggle-btn .badge').hasClass('d-none') == true){
            $('.app-theme-toggle-btn .badge').removeClass('d-none');
            $('.app-theme-toggle-btn .badge').text(chatCount);
        }else{
            $('.app-theme-toggle-btn .badge').text(chatCount);
        }
    }

    last_user = message.sender_id;
    last_day = formatDateASC(message.created_at, '-');
}

/*Listen Chat Message*/
function showChatNotify(notify_id, title, message) {
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
        autohide: true,
        delay: 3000
    }).toast('show');
}

function playSound() {
    // Replace 'path/to/your/sound.wav' with the actual path to your WAV sound file
    const audio = new Audio('COMCell_Message.wav');
    audio.play();
}

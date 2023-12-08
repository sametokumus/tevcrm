Pusher.logToConsole = true;

var pusher = new Pusher('c52432e0f85707bee8d3', {
    cluster: 'eu'
});

var channel = pusher.subscribe('company-chat-channel');
channel.bind('App\\Events\\CompanyChat', function(data) {
    console.log('Received message: ', data);
    let received = JSON.stringify(data);
    // Handle the received message as needed
    handleSendMessageEvent(data)
});

function handleSendMessageEvent(data) {

    // Update the chat interface or append the message to the DOM
    const chatMessagesDiv = document.getElementById('chat-messages');
    const messageElement = document.createElement('p');
    messageElement.innerHTML = data.user.name + ' ' + data.user.surname + ': <br>' + data.message + '<br>';
    chatMessagesDiv.appendChild(messageElement);

    // Scroll to the bottom to show the latest message
    chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
}

// mesaj gönderme

document.addEventListener('DOMContentLoaded', function () {


    // Event listener for the chat form submission
    document.getElementById('chat-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();

        let formData = JSON.stringify({
            "message": message,
            "user_id": localStorage.getItem('userId')
        });
        let returned = servicePostCompanyChatMessage(formData);
        if (!returned){
            alert('Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyiniz!');
        }

    });


});

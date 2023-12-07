Pusher.logToConsole = true;

var pusher = new Pusher('c52432e0f85707bee8d3', {
    cluster: 'eu'
});

var channel = pusher.subscribe('chat-channel');
channel.bind('App\\Events\\SendMessage', function(data) {
    console.log('Received message: ', data);
    let received = JSON.stringify(data);
    // Handle the received message as needed
    handleSendMessageEvent(data.message)
});

function handleSendMessageEvent(message) {

    // Update the chat interface or append the message to the DOM
    const chatMessagesDiv = document.getElementById('chat-messages');
    const messageElement = document.createElement('p');
    messageElement.innerHTML = message;
    chatMessagesDiv.appendChild(messageElement);

    // Scroll to the bottom to show the latest message
    chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
}

// mesaj gönderme

document.addEventListener('DOMContentLoaded', function () {


    // Event listener for the chat form submission
    document.getElementById('chat-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();

        // Create a new FormData object
        const formData = new FormData();
        formData.append('message', message);

        // Make a POST request using fetch
        fetch('/chat/sendMessage', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken, // Include the CSRF token in the headers
            },
            body: formData,
        })
            .then(response => response.json())
            .then(data => console.log(data))
            .catch(error => console.error('Error:', error));

    });


});

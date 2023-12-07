<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat App</title>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script src="{{ asset('services/chat.js') }}" defer></script>

</head>
<body>
<div>
    <h1>Chat App</h1>

    <div class="container">
        <div class="row">
            <div id="chat-messages" style="width: 300px; border: 1px solid #ccc; padding: 10px; height: 200px; overflow-y: auto; margin-bottom: 30px;"></div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <form id="chat-form">
                <input type="text" id="message" placeholder="Type your message">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>


</div>

</body>
</html>

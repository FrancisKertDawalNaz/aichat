<!DOCTYPE html>
<html>
<head>
    <title>AI Chat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>AI Chat</h2>
    <div id="chat-box">
        @foreach($messages as $msg)
            <p><b>{{ $msg->sender }}:</b> {{ $msg->message }}</p>
        @endforeach
    </div>

    <input type="text" id="message">
    <button onclick="sendMessage()">Send</button>

    <script>
        function sendMessage() {
            let message = document.getElementById('message').value;
            let chatBox = document.getElementById('chat-box');

            fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                chatBox.innerHTML += "<p><b>user:</b> " + message + "</p>";
                chatBox.innerHTML += "<p><b>ai:</b> " + data.reply + "</p>";
            });
        }
    </script>
</body>
</html>

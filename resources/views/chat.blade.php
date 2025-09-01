<!DOCTYPE html>
<html>

<head>
    <title>AI Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="chat-container">
        <div class="chat-header">
            🤖 AI Chat
        </div>
        <div id="chat-box" class="chat-box">
            @foreach($messages as $msg)
            <div class="chat-message {{ $msg->sender }}">
                <div class="chat-bubble">
                    {{ $msg->message }}
                </div>
            </div>
            @endforeach
        </div>
        <div class="chat-input">
            <input type="text" id="message" class="form-control" placeholder="Type a message..." onkeypress="if(event.key === 'Enter'){sendMessage()}">
            <button class="btn btn-primary" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        function sendMessage() {
            let message = document.getElementById('message').value;
            if (!message.trim()) return;
            let chatBox = document.getElementById('chat-box');

            fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML += `
                    <div class="chat-message user">
                        <div class="chat-bubble">${message}</div>
                    </div>`;
                    chatBox.innerHTML += `
                    <div class="chat-message ai">
                        <div class="chat-bubble">${data.reply}</div>
                    </div>`;
                    document.getElementById('message').value = '';
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
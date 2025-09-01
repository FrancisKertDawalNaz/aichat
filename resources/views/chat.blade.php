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
        <div class="chat-input d-flex gap-2">
            <input type="text" id="message" class="form-control" placeholder="Type a message..."
                onkeypress="if(event.key === 'Enter'){sendMessage()}">
            <button id="send-btn" class="btn btn-primary" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        const messageInput = document.getElementById('message');
        const sendBtn = document.getElementById('send-btn');

        // Auto-scroll on page load
        window.onload = () => {
            chatBox.scrollTop = chatBox.scrollHeight;
        };

        function escapeHtml(text) {
            let div = document.createElement("div");
            div.innerText = text;
            return div.innerHTML;
        }

        async function sendMessage() {
            let message = messageInput.value.trim();
            if (!message) return;

            // Append user message instantly
            chatBox.innerHTML += `
                <div class="chat-message user">
                    <div class="chat-bubble">${escapeHtml(message)}</div>
                </div>`;
            chatBox.scrollTop = chatBox.scrollHeight;

            // Show typing indicator
            let typingEl = document.createElement("div");
            typingEl.className = "chat-message ai typing";
            typingEl.innerHTML = `<div class="chat-bubble">...</div>`;
            chatBox.appendChild(typingEl);
            chatBox.scrollTop = chatBox.scrollHeight;

            // Clear input
            messageInput.value = '';
            sendBtn.disabled = true;

            try {
                let res = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });
                let data = await res.json();

                // Remove typing indicator
                typingEl.remove();

                // Append AI reply
                chatBox.innerHTML += `
                    <div class="chat-message ai">
                        <div class="chat-bubble">${escapeHtml(data.reply)}</div>
                    </div>`;
                chatBox.scrollTop = chatBox.scrollHeight;

            } catch (err) {
                typingEl.remove();
                chatBox.innerHTML += `
                    <div class="chat-message ai">
                        <div class="chat-bubble text-danger">Error: Could not send message.</div>
                    </div>`;
            }

            sendBtn.disabled = false;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

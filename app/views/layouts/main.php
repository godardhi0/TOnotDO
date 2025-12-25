<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TOnotDO</title>
    <link rel="icon" href="/TOnotDO/public/img/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="/TOnotDO/public/css/chat.css">
</head>
<body>

<!-- Inject the view -->
<?php
$fullViewPath = $viewFile; // from Controller.php
?>

<?php $currentUser = Auth::user(); ?>

<!-- Site header with logo -->
<header class="site-header">
    <a href="/TOnotDO/public/"><img src="/TOnotDO/public/img/logo0.svg" alt="TOnotDO" class="site-logo"></a>
</header>

<!-- Socket.IO scripts (initialized before views so view scripts can use `socket`) -->
<script src="http://localhost:3000/socket.io/socket.io.js"></script>
<script>
    // ensure we don't leak multiple connections when navigating
    if (window.socket && typeof window.socket.disconnect === 'function') {
        window.socket.disconnect();
    }

    // attach to window so view scripts can access it
    window.socket = io("http://localhost:3000");

    // On task updates we refresh the page (can be made more granular later)
    window.socket.on("taskUpdate", data => {
        location.reload(); // simple refresh
    });
</script>

<?php
require $fullViewPath;
?>

    <!-- Floating chat bubble available on all pages -->
    <button id="chat-toggle" class="chat-bubble" aria-label="Open chat">💬</button>

    <!-- Chat panel (hidden by default) -->
    <div id="chat-panel" class="chat-panel" role="region" aria-label="Chat panel">
        <div class="chat-panel__header">
            <strong>Chat</strong>
            <button id="chat-close" class="chat-panel__close" aria-label="Close chat">✕</button>
        </div>
        <div id="chat-messages" class="chat-panel__messages" aria-live="polite"></div>
        <div class="chat-panel__input-area">
            <input id="chat-input" class="chat-panel__input" placeholder="Tapez un message......" />
            <button id="chat-send" class="chat-panel__send">Envoyer</button>
        </div>
    </div>

    <script>
    // Chat panel toggle logic
    (function(){
        const toggle = document.getElementById('chat-toggle');
        const panel = document.getElementById('chat-panel');
        const closeBtn = document.getElementById('chat-close');
        const sendBtn = document.getElementById('chat-send');
        const input = document.getElementById('chat-input');
        const messages = document.getElementById('chat-messages');

        function openPanel(){ panel.classList.add('open'); input.focus(); }
        function closePanel(){ panel.classList.remove('open'); }

        toggle.addEventListener('click', function(e){ e.preventDefault(); if(!panel.classList.contains('open')) openPanel(); else closePanel(); });
        closeBtn.addEventListener('click', closePanel);

        // Determine username from server-side Auth (rendered by PHP) — use safe $currentUser
        const CHAT_USERNAME = "<?= htmlspecialchars($currentUser['username'] ?? 'Visteur') ?>";

        // Use shared socket from layout
        const socket = window.socket;

        // Ensure we attach chat handler only once
        if(socket && !window.__chatPanelInitialized){
            socket.on('chatMessage', function(msg){
                const div = document.createElement('div');
                const prefix = (msg.from === 'bot') ? '⚙️: ' : (msg.from + ': ');
                div.innerText = prefix + msg.text;
                messages.appendChild(div);
                div.scrollIntoView();
            });
            window.__chatPanelInitialized = true;
        }

        function appendOwn(text){
            const div = document.createElement('div');
            div.innerText = '👤 ' + CHAT_USERNAME + ': ' + text;
            messages.appendChild(div);
            div.scrollIntoView();
        }

        function sendMessage(){
            const text = input.value.trim();
            if(!text) return;
            if(!socket){ console.error('Socket not available'); return; }
            appendOwn(text);
            socket.emit('chatMessage', { from: CHAT_USERNAME, text: text });
            input.value = '';
        }

        sendBtn.addEventListener('click', sendMessage);
        input.addEventListener('keydown', function(e){ if(e.key === 'Enter') sendMessage(); });
    })();
    </script>
</body>
</html>

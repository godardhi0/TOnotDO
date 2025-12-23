<h2>Chat</h2>

<div id="messages" style="height:300px; border:1px solid #ccc; overflow-y:auto; padding:5px;"></div>

<input id="msg" placeholder="Type a message..." />
<button onclick="send()">Send</button>

<script>
const username = "<?= htmlspecialchars($username ?? 'Guest') ?>";

// Use socket initialized by layout
const socket = window.socket;

function send() {
    const text = document.getElementById("msg").value.trim();
    if (text === "") return;
    if (!socket) {
        console.error('Socket not available');
        return;
    }
    
    // Immediately append user's message to the display
    const div = document.createElement("div");
    div.innerText = "👤 " + username + ": " + text;
    document.getElementById("messages").appendChild(div);
    div.scrollIntoView();
    
    // Emit to server
    socket.emit("chatMessage", { from: username, text: text });
    document.getElementById("msg").value = '';
}

if (socket) {
    socket.on("chatMessage", msg => {
        const div = document.createElement("div");
        const prefix = (msg.from === "bot") ? "🤖 " : ("👤 " + msg.from + ": ");
        div.innerText = prefix + msg.text;
        document.getElementById("messages").appendChild(div);
        // Scroll to bottom
        div.scrollIntoView();
    });
} else {
    console.warn('Socket not connected; incoming messages will not be displayed.');
}
</script>

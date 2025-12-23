<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TOnotDO</title>
</head>
<body>

<!-- Inject the view -->
<?php
$fullViewPath = $viewFile; // from Controller.php

?>

<!-- Socket.IO scripts (initialized before views so view scripts can use `socket`) -->
<script src="http://localhost:3000/socket.io/socket.io.js"></script>
<script>
    console.log("🚨 socket script loaded");

    // ensure we don't leak multiple connections when navigating
    if (window.socket && typeof window.socket.disconnect === 'function') {
        window.socket.disconnect();
    }

    // attach to window so view scripts can access it
    window.socket = io("http://localhost:3000");

    window.socket.on("taskUpdate", data => {
        console.log("Task updated:", data);
        location.reload(); // simple refresh
    });

    window.socket.on("chatMessage", msg => {
        console.log("Chat (layout):", msg);
    });
</script>

<?php
require $fullViewPath;
?>

<!-- Socket.IO scripts -->
<script src="http://localhost:3000/socket.io/socket.io.js"></script>
<script>
    console.log("🚨 socket script loaded");
    const socket = io("http://localhost:3000");

    socket.on("taskUpdate", data => {
        console.log("Task updated:", data);
        location.reload(); // simple refresh
    });

    socket.on("chatMessage", msg => {
        console.log("Chat:", msg);
    });
</script>
<nav>
    <?php 
        $isChatPage = ($view ?? '') === 'chat/index';
        $isLoginPage = ($view ?? '') === 'auth/login' || ($view ?? '') === 'auth/register';
        $isLoggedIn = Auth::check();
        
        if ($isLoggedIn && !$isChatPage && !$isLoginPage): 
    ?>
        <a href="/TOnotDO/public/chat/index">Chat</a>
    <?php endif; ?>
</nav>


</body>
</html>

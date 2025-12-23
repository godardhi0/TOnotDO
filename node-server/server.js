const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const app = express();
app.use(cors());

const server = http.createServer(app);

const io = new Server(server, {
    cors: {
        origin: "http://localhost",
        methods: ["GET", "POST"]
    }
});

io.on("connection", (socket) => {
    console.log("🔥 Browser connected:", socket.id);

    // Chat messages
    socket.on("chatMessage", msg => {
        let reply = "Sorry, I did not understand.";

        const text = msg.text.toLowerCase();

        if (text.includes("task")) {
            reply = "Tasks depend on your role: client creates, worker completes, root assigns.";
        } else if (text.includes("profile")) {
            reply = "You can manage your profile from your dashboard.";
        } else if (text.includes("login")) {
            reply = "Use your credentials on the login page.";
        }

        socket.emit("chatMessage", {
            from: "bot",
            text: reply
        });
        
        socket.broadcast.emit("chatMessage", { from: msg.from, text: msg.text });

    });


    // Task updates
    socket.on("taskUpdate", payload => {
        console.log("📋 Task update:", payload);
        io.emit("taskUpdate", payload);
    });

    socket.on("disconnect", () => {
        console.log("❌ Browser disconnected:", socket.id);
    });
});

server.listen(3000, () => {
    console.log("✅ Node.js server running on port 3000");
});

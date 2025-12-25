// import required modules
    // CommonJS module: require()

// express for server framework : 
const express = require("express");
// http for creating server : 
    //The http module in Node.js allows you to create an HTTP server that can listen for and respond to HTTP requests.
const http = require("http");

// socket.io for real-time communication :
    //Socket.IO is a library that enables real-time, bidirectional and event-based communication between web clients and servers.
const { Server } = require("socket.io");

//CORS for cross-origin requests
    //CORS (Cross-Origin Resource Sharing) is a security feature implemented by web browsers to restrict web pages from making requests to a different domain than the one that served the web page.
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
    console.log("Le navigateur est connecté:", socket.id);

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
        console.log("Navigateur deconnecté:", socket.id);
    });
});

server.listen(3000, () => {
    console.log("Le serveur Node.js écoute sur le port 3000");
});

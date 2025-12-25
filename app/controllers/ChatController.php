<?php
require_once ROOT . '/app/core/Controller.php';

class ChatController extends Controller
{
    // All logged-in users can access chat
    public function index()
    {
        // Allow guests to access chat: don't require login here
        $user = Auth::user();
        $username = $user['username'] ?? 'Guest';

        // Pass the current username to the view
        $this->view('chat/index', ['username' => $username, 'isGuest' => $user === null]);
    }
}

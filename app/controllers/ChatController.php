<?php
require_once ROOT . '/app/core/Controller.php';

class ChatController extends Controller
{
    // All logged-in users can access chat
    public function index()
    {
        Auth::check(); // ensures user is logged in

        // Pass the current username to the view (optional)
        $this->view('chat/index', ['username' => Auth::user()['username']]);
    }
}

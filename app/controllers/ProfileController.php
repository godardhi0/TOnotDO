<?php

require_once ROOT . '/app/models/User.php';

class ProfileController extends Controller
{
    public function show()
    {
        Auth::requireLogin();
        $this->view('profile/show', ['user' => Auth::user()]);
    }

    public function edit()
    {
        Auth::requireLogin();
        $this->view('profile/edit', ['user' => Auth::user()]);
    }

    public function update()
    {
        Auth::requireLogin();

        $userModel = new User();
        $userModel->update(Auth::user()['id'], $_POST);

        $_SESSION['user']['username'] = $_POST['username'];
        $_SESSION['user']['email'] = $_POST['email'];

        $this->redirect('profile/show');
    }

    public function changePassword()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->updatePassword(Auth::user()['id'], $_POST['password']);
            $this->redirect('profile/show');
        }

        $this->view('profile/password');
    }

    public function delete()
    {
        Auth::requireLogin();

        $userModel = new User();
        $userModel->delete(Auth::user()['id']);
        session_destroy();

        $this->redirect('auth/register');
    }
}


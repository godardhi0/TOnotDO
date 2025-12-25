<?php
require_once "../app/models/User.php";

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $userModel = new User();
            
            $user = $userModel->findByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user'] = $user;

                // login successful, redirect to profile
                $this->redirect('profile/show');
            } else {
                $error = "Mot de passe ou adresse e-mail invalides";
            }
        }

        $this->view('auth/login', ['error' => $error ?? null]);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->create($_POST);
            $this->redirect('auth/login');
        }

        $this->view('auth/register');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('auth/login');
    }
}

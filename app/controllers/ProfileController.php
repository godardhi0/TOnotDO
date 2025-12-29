<?php
require_once ROOT . '/app/models/User.php';
require_once ROOT . '/app/controllers/TasksController.php';

class ProfileController extends Controller
{
    private $Tasks;

    public function __construct()
    {
        $this->Tasks = new TasksController();
    }
    
    public function show()
    {
        Auth::requireLogin();
        // ensure $tasks is defined for all roles to avoid undefined variable warnings
        $tasks = [];

        if(Auth::user()['role'] === 'client'){
            $tasks = $this->Tasks->myRequests();
        } elseif (Auth::user()['role'] === 'root'){
            $tasks = $this->Tasks->manage();
            // Load workers so the root create-task form can list assignable workers
            $userModel = new User();
            $workers = $userModel->findbyRole_('worker');
        } elseif (Auth::user()['role'] === 'worker') {
            // Provide the worker's tasks to the profile view
            $tasks = $this->Tasks->myTasksList();
        }
        $viewData = ['tasks' => $tasks, 'user' => Auth::user()];
        if (isset($workers)) $viewData['workers'] = $workers;
        $this->view('profile/show', $viewData);
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


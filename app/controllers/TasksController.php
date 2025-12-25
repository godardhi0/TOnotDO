<?php
require_once ROOT . '/app/models/Task.php';
require_once ROOT . '/app/models/User.php';
class TasksController extends Controller
{
    public function myRequests()
    {
        // Only clients can access their demands 
        Auth::role('client');

        $taskModel = new Task();
        $tasks = $taskModel->getByClient(Auth::user()['id']);

        // Render the view with tasks
        $this->view('tasks/client', ['tasks' => $tasks]);
    }

    public function myTasks()
    {
        Auth::role('worker');

        $taskModel = new Task();
        $tasks = $taskModel->getByWorker(Auth::user()['id']);

        $this->view('tasks/worker', ['tasks' => $tasks]);
    }

    public function manage()
    {
        Auth::role('root');

        $taskModel = new Task();
        $tasks = $taskModel->getAll();

        $this->view('tasks/root', ['tasks' => $tasks]);
    }

    public function create()
    {
        Auth::role(['client', 'root']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskModel = new Task();

            // Determine client_id: if root provided a client selection, use it,
            // otherwise use the current user's id (for clients)
            $clientId = null;
            $currentUser = Auth::user();
            if (isset($currentUser['role']) && $currentUser['role'] === 'root' && !empty($_POST['client_id'])) {
                $clientId = (int) $_POST['client_id'];
            } else {
                $clientId = $currentUser['id'] ?? null;
            }

            $taskModel->create([
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'client_id' => $clientId
            ]);

            // Redirect depending on role
            if (isset($currentUser['role']) && $currentUser['role'] === 'root') {
                $this->redirect('tasks/manage');
            } else {
                $this->redirect('tasks/myRequests');
            }
        }

        // If the current user is root, pass the list of clients to the view
        $currentUser = Auth::user();
        if (isset($currentUser['role']) && $currentUser['role'] === 'root') {
            $userModel = new User();
            // Use User model to fetch clients by role
            $clients = $userModel->findByRole('client');

            $this->view('tasks/create', ['clients' => $clients]);
        } else {
            $this->view('tasks/create');
        }
    }


    public function delete($id)
    {
        Auth::role('root');

        $taskModel = new Task();
        $taskModel->delete($id);
        $this->redirect('tasks/manage');
    }

    public function edit($id)
    {
        Auth::role('root');

        $taskModel = new Task();
        $task = $taskModel->find($id);

        $db = Database::getInstance();
        $workers = $db->query("SELECT id, username FROM users WHERE role='worker'")
                    ->fetchAll(PDO::FETCH_ASSOC);

        $this->view('tasks/edit', [
            'task' => $task,
            'workers' => $workers
        ]);
    }

    public function update($id)
    {
        Auth::role('root');

        $taskModel = new Task();
        $taskModel->update($id, $_POST);

        $this->notifyNode([
            'type' => 'taskUpdate',
            'task_id' => $id
        ]);

        $this->redirect('tasks/manage');
    }

    public function complete($id)
    {
        Auth::role('worker');

        $taskModel = new Task();
        $taskModel->updateStatus($id, 'done');

        $this->notifyNode([
            'type' => 'taskUpdate',
            'task_id' => $id
        ]);

        $this->redirect('tasks/myTasks');
    }

    private function notifyNode($data)
    {
        $ch = curl_init("http://localhost:3000");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

}

<?php
require_once ROOT . '/app/models/Task.php';
require_once ROOT . '/app/models/User.php';
class TasksController extends Controller
{
    public function myRequests()
    {
        // Only clients can access their demands 
        Auth::role(['client', 'root']);

        $taskModel = new Task();
        $tasks = $taskModel->getByClient(Auth::user()['id']);
        
        // Render the view with tasks
        //$this->view('profile/show', ['tasks' => $tasks, 'user' => Auth::user()]);
        return $tasks;  
    }

    public function myTasks()
    {
        Auth::role('worker');

        $taskModel = new Task();
        $tasks = $taskModel->getByWorker(Auth::user()['id']);

        $this->view('tasks/worker', ['tasks' => $tasks]);
    }

    // Return tasks for the current worker (without rendering a view)
    public function myTasksList()
    {
        Auth::role('worker');

        $taskModel = new Task();
        return $taskModel->getByWorker(Auth::user()['id']);
    }

    public function manage()
    {
        Auth::role('root');

        $taskModel = new Task();
        $tasks = $taskModel->getAll();
        return $tasks;
        //echo "here";
        //$this->view('profile/show', ['tasks' => $tasks, 'user' => Auth::user()]);
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

            // If a root assigns a worker via the form, include it
            $workerId = null;
            if (!empty($_POST['assigned_to'])) {
                $workerId = (int) $_POST['assigned_to'];
            }

            $taskModel->create([
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'client_id' => $clientId,
                'worker_id' => $workerId
            ]);

            $this->redirect('profile/show');
        }
    }


    public function delete($id)
    {
        Auth::role('root');

        $taskModel = new Task();
        $taskModel->delete($id);
        $this->redirect('profile/show');
    }

    public function edit($id)
    {
        Auth::role('root');

        $taskModel = new Task();
        $task = $taskModel->find($id);

        $UserModel = new User();
        $workers = $UserModel->findbyRole_('worker');

        $this->view('tasks/edit', [
            'task' => $task,
            'workers' => $workers,
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

        $this->redirect('profile/show');
    }

    public function complete($id)
    {
        Auth::role('worker');

        $taskModel = new Task();
        $taskModel->updateStatus($id, 'terminée');

        $this->notifyNode([
            'type' => 'taskUpdate',
            'task_id' => $id
        ]);

        // If this is an AJAX request, return a JSON response instead of redirecting
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'task_id' => (int)$id, 'status' => 'terminée']);
            return;
        }

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

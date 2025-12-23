<?php
require_once ROOT . '/app/models/Task.php';
class TasksController extends Controller
{
    public function myRequests()
    {
        Auth::role('client');

        $taskModel = new Task();
        $tasks = $taskModel->getByClient(Auth::user()['id']);

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
        // Allow both client and root
        Auth::role(['client', 'root']); 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskModel = new Task();
            $taskModel->create([
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'client_id' => Auth::user()['id'] ?? null // or assign a client if root
            ]);

            $this->redirect('tasks/myRequests'); // or tasks/manage for root
        }

        $this->view('tasks/create');
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

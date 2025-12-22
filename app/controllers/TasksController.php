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
        Auth::role('client');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $taskModel = new Task();
            $taskModel->create([
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'client_id' => Auth::user()['id']
            ]);
            $this->redirect('tasks/myRequests');
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

        $this->redirect('tasks/manage');
    }

    public function complete($id)
    {
        Auth::role('worker');

        $taskModel = new Task();
        $taskModel->updateStatus($id, 'done');

        $this->redirect('tasks/myTasks');
    }

}

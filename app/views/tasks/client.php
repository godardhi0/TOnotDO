<h2>My Requests</h2>

<a href="/TOnotDO/public/tasks/create">Create Task</a>

<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <strong><?= htmlspecialchars($task['title']) ?></strong>
        — Status:
        <span style="color: <?= $task['status'] === 'done' ? 'green' : 'black' ?>">
            <?= $task['status'] ?>
        </span>
    </li>
<?php endforeach; ?>
</ul>

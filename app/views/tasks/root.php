
<h2>All Tasks</h2>

<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <strong><?= htmlspecialchars($task['title']) ?></strong>
        — Status: <?= $task['status'] ?>

        <a href="/TOnotDO/public/tasks/edit/<?= $task['id'] ?>">Edit</a>
        <a href="/TOnotDO/public/tasks/delete/<?= $task['id'] ?>">Delete</a>
    </li>
<?php endforeach; ?>
</ul>

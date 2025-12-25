
<h2>My Requests</h2>
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

<h2>My Tasks</h2>

<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <strong><?= htmlspecialchars($task['title']) ?></strong>
        — Status: <?= $task['status'] ?>

        <?php if ($task['status'] !== 'done'): ?>
            <a href="/TOnotDO/public/tasks/complete/<?= $task['id'] ?>">
                Mark as done
            </a>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>

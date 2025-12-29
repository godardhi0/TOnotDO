<h2>Mes demandes:</h2>
<ul>
<?php foreach ($tasks as $task): ?>
    <li>
        <strong>titre:</strong>
        <strong><?= htmlspecialchars($task['title']) ?></strong>
        <br>
        <strong>statut: </strong>
        <span style="color: <?= $task['status'] === 'terminée' ? 'green' : 'violet' ?>">
            <strong><?= $task['status'] ?></strong>
        </span>
        <br>
        <strong>créée:</strong>
        <?= $task['created_at']?>
        <br>
        <p><?= $task['description']?></p>      
    </li>
<?php endforeach; ?>
</ul>



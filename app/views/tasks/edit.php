<h2>Edit Task</h2>

<form method="POST" action="/TOnotDO/public/tasks/update/<?= $task['id'] ?>">
    <input name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
    <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea>

    <select name="worker_id">
        <option value="">Unassigned</option>
        <?php foreach ($workers as $w): ?>
            <option value="<?= $w['id'] ?>" <?= $task['worker_id'] == $w['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($w['username']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button>Save</button>
</form>

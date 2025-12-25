
<h2>Create Task</h2>

<form method="POST">
    <input type="text" name="title" placeholder="Title" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>

    <?php if (!empty($clients)): ?>
        <label for="client_id">Assign to client:</label>
        <select name="client_id" id="client_id">
            <option value="">-- select client --</option>
            <?php foreach ($clients as $client): ?>
                <option value="<?= $client['id'] ?>"><?= htmlspecialchars($client['username']) ?></option>
            <?php endforeach; ?>
        </select><br>
    <?php endif; ?>

    <button type="submit">Create</button>
</form>

<link rel="stylesheet" href="/TOnotDO/public/css/edit.css">
<div class="auth-wrap">
    <div class="auth-card" role="main">
        <h2>Modifier le profil</h2>
        <form method="POST">
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>

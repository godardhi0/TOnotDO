<h2>Edit Profile</h2>

<form method="POST" action="/TOnotDO/public/profile/update">
    <input name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    <input name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <button>Save</button>
</form>

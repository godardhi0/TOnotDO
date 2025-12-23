 <title>Login</title>
<h2>Login</h2>
<?php if (!empty($error)) : ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
<a href="/TOnotDO/public/auth/register">Register</a>

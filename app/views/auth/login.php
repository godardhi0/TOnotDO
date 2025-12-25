<link rel="stylesheet" href="/TOnotDO/public/css/login.css">

<div class="auth-wrap">
    <div class="auth-card" role="main">
        <h2>Connectez-vous</h2>

        <?php if (!empty($error)) : ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <!--<label class="sr-only">E-mail</label>-->
            <input type="email" name="email" placeholder="Email" required autofocus>

            <!--<label class="sr-only">Mot de passe</label> -->
            <input type="password" name="password" placeholder="Mot de passe" required>

            <button type="submit">connexion</button>
        </form>

        <div class="muted">Vous n’avez pas de compte ?<a class="small" href="/TOnotDO/public/auth/register"> S'inscrire</a></div>
    </div>
</div>

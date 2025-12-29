<link rel="stylesheet" href="/TOnotDO/public/css/register.css">
<div class="auth-wrap">
    <div class="auth-card" role="main">
        <h2>S'inscrire</h2>

        <form method="POST">
            <input type="text" name="username" placeholder="Identifiant" required autofocus>
            <input type="email" name="email" placeholder="Email" required >
            <input type="password" name="password" placeholder="Mot de passe" required >

            <select class="role" type="selected" name="rôle" required>
                <option value="" disabled selected><strong>Choisir un rôle</strong></option>
                <option value="client">Client</option>
                <option value="worker">Travailleur</option>
                <option value="root">Admin</option>
            </select>

            <button type="submit">S'inscrire</button>
        </form>

        <div class="muted">Avez vous un compte ? <a class="small"href="/TOnotDO/public/auth/login">Se connecter</a></div>
    </div>
</div>

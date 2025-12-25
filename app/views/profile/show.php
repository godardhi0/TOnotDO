<link rel="stylesheet" href="/TOnotDO/public/css/profile.css">

<div class="profile-wrapper">
    <!-- Hero welcome section -->
    <div class="profile-hero">
        <div class="profile-hero__content">
            <h1 class="profile-hero__title">Bienvenue, <strong><?= htmlspecialchars($user['username']) ?></strong></h1>
            <p class="profile-hero__subtitle">Gérer votre compte et vos tâches à partir d’ici</p>
        </div>
    </div>

    <!-- Main actions section -->
    <div class="profile-actions">
        <!-- Primary action: Go to tasks -->
        <div class="action-primary">
            <?php if ($user['role'] === 'client'): ?>
                <a href="/TOnotDO/public/tasks/myRequests" class="btn btn-primary">
                    📋 Mes demandes
                </a>
                <!-- <p class="action-desc">Consulter vos demandes</p> -->
                <a href="/TOnotDO/public/tasks/create" class="btn btn-primary">
                    ➕ Créer une demande
                </a>
                <!-- <p class="action-desc">Créer une nouvelle demande</p> -->
            <?php elseif ($user['role'] === 'worker'): ?>
                <a href="/TOnotDO/public/tasks/myTasks" class="btn btn-primary">
                    ✓ My Tasks
                </a>
                <p class="action-desc">View and complete your assigned tasks</p>
            <?php elseif ($user['role'] === 'root'): ?>
                <a href="/TOnotDO/public/tasks/manage" class="btn btn-primary">
                    ⚙ Gérer les tâches
                </a>
                <p class="action-desc">Créer, attribuer et surveiller toutes les tâches</p>
            <?php endif; ?>
            
            <!-- Button to show/hide profile card -->
            <button id="btn-show-profile" class="btn btn-secondary" aria-expanded="false" type="button">
                👤 Voir le profil
            </button>
        </div>

        <!-- User info card (hidden by default, toggled with button) -->
        <div class="profile-card hidden" id="profile-card">
            <div class="profile-card__section">
                <h2 class="profile-card__heading">Information de profil: </h2>
                <div class="profile-info">
                    <div class="profile-info__row">
                        <span class="profile-info__label">Mon adresse mail:</span>
                        <span class="profile-info__value"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <div class="profile-info__row">
                        <span class="profile-info__label">Mon rôle:</span>
                        <span class="profile-info__badge" data-role="<?= htmlspecialchars($user['role']) ?>"><?= htmlspecialchars(ucfirst($user['role'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dropdown menu for account actions -->
        <div class="action-dropdown">
            <button id="profile-dropdown-toggle" class="dropdown-toggle" aria-label="More options" aria-expanded="false">
                ⋯ MENU
            </button>
            <div id="profile-dropdown-menu" class="dropdown-menu">
                <a href="/TOnotDO/public/profile/edit" class="dropdown-item">
                    ✏ Modifier mon profil
                </a>
                <a href="/TOnotDO/public/profile/changePassword" class="dropdown-item">
                    🔑 Change mon mot de passe
                </a>
                <a href="/TOnotDO/public/profile/delete" class="dropdown-item dropdown-item--danger">
                    🗑️ Supprimer mon compte
                </a>

                <a method="POST"href="/TOnotDO/public/auth/logout" class="dropdown-item dropdown-item--danger">
                    🚪 Déconnexion
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const toggle = document.getElementById('profile-dropdown-toggle');
    const menu = document.getElementById('profile-dropdown-menu');

    if(toggle && menu){
        toggle.addEventListener('click', function(){
            const isOpen = menu.classList.contains('open');
            if(isOpen) menu.classList.remove('open');
            else menu.classList.add('open');
            toggle.setAttribute('aria-expanded', !isOpen);
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e){
            if(!toggle.contains(e.target) && !menu.contains(e.target)){
                menu.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
})();
</script>

<script>
// Toggle profile card visibility
(function(){
    const btn = document.getElementById('btn-show-profile');
    const card = document.getElementById('profile-card');
    if(!btn || !card) return;

    btn.addEventListener('click', function(){
        const isHidden = card.classList.toggle('hidden');
        // aria-expanded should be true when card is visible
        btn.setAttribute('aria-expanded', (!isHidden).toString());
    });
})();
</script>

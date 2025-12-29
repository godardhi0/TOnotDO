<link rel="stylesheet" href="/TOnotDO/public/css/profile.css">

<div class="profile-wrapper">
    <!-- Hero welcome section -->
    <div class="profile-hero" style="position: relative;">
        <div class="profile-hero__content">
            <h1 class="profile-hero__title">Bienvenue, <strong><?= htmlspecialchars($user['username']) ?></strong></h1>
            <p class="profile-hero__subtitle">Gérer votre compte et vos tâches à partir d’ici</p>
        </div>

        <!-- Dropdown menu for account actions (positioned to the right of welcome text) -->
        <div class="action-dropdown" style="position: absolute; right: 24px; top: 50%; transform: translateY(-50%); z-index: 999;">
            <button id="profile-dropdown-toggle" class="dropdown-toggle" aria-label="More options" aria-expanded="false">
                ⋯ MENU
            </button>
            <div id="profile-dropdown-menu" class="dropdown-menu" style="position: absolute; left: 100%; top: 0; margin-left: 8px; min-width: 200px; visibility: hidden; opacity: 0;">
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

    <!-- Main actions section -->
    <div class="profile-actions">
        <!-- Primary action: Go to tasks -->
        <div class="action-primary">
            <?php if ($user['role'] === 'client'): ?>
                <button id="btn-mes-demandes" aria-expanded="false" class="btn btn-primary" type="button">
                    📋 Mes demandes
                </button>
                <!-- <p class="action-desc">Consulter vos demandes</p> -->
                <button id="btn-create-request" aria-expanded="false" class="btn btn-primary" type="button">
                    ➕ Créer une demande
                </button>
                <!-- <p class="action-desc">Créer une nouvelle demande</p> -->
            <?php elseif ($user['role'] === 'worker'): ?>
                <button id="btn-my-tasks" aria-expanded="false" class="btn btn-primary" type="button">✓ Mes tâches</button>
                
            <?php elseif ($user['role'] === 'root'): ?>
                <button id="btn-manage-tasks" aria-expanded="false" class="btn btn-primary" type="button">⚙ Gérer les tâches</button>
                <button id="btn-create-task" aria-expanded="false" class="btn btn-primary" type="button">➕ Créer une tâche</button>
            <?php endif; ?>
            
            <!-- Button to show/hide profile card -->
            <button id="btn-show-profile" class="btn btn-secondary" aria-expanded="false" type="button">
                👤 Voir le profil
            </button>
        </div>

        <!-- CLIENT: List of requests (Mes demandes) -->
        <?php if ($user['role'] === 'client' && isset($tasks)): ?>
            <div class="profile-card hidden" id="mes-demandes-panel">
                <div class="profile-card__section">
                    <h2 class="profile-card__heading">Mes demandes:</h2>
                    <div class="profile-info" style="max-height:420px; overflow-y:auto; padding-right:8px;">
                        <?php if (empty($tasks)): ?>
                            <p style="padding: 20px; text-align: center; color: #666;">Vous n'avez aucune demande pour le moment.</p>
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                                <div class="task-card-item" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 16px; background: #fafafa; transition: box-shadow 0.3s ease;">
                                    <!-- Title and Status Header -->
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <h3 style="margin: 0; font-size: 18px; color: #333; font-weight: 600;">
                                            <?= htmlspecialchars($task['title']) ?>
                                        </h3>
                                        <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background-color: <?= $task['status'] === 'terminée' ? '#4CAF50' : '#9C27B0' ?>;">
                                            <?= htmlspecialchars($task['status']) ?>
                                        </span>
                                    </div>

                                    <!-- Date -->
                                    <div style="display: flex; align-items: center; margin-bottom: 16px; color: #666; font-size: 13px;">
                                        <span style="margin-right: 8px;">📅</span>
                                        <span>Créée le: <strong><?= htmlspecialchars($task['created_at']) ?></strong></span>
                                    </div>

                                    <!-- Description -->
                                    <div style="background: white; padding: 12px; border-radius: 6px; color: #555; line-height: 1.6; font-size: 14px;">
                                        <?= nl2br(htmlspecialchars($task['description'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CLIENT: Create request form -->
            <div class="profile-card hidden" id="create-request-panel">
                <div class="profile-card__section">
                    <h2 class="profile-card__heading">Créer une nouvelle demande:</h2>
                    <div class="profile-info">
                        <div class="profile-info__row">
                            <form method="POST" action="/TOnotDO/public/tasks/create">
                                <div style="margin-bottom: 15px;">
                                    <label for="title" style="display: block; margin-bottom: 5px; font-weight: bold;">Titre:</label>
                                    <input type="text" id="title" name="title" placeholder="Titre de votre demande" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <label for="description" style="display: block; margin-bottom: 5px; font-weight: bold;">Description:</label>
                                    <textarea id="description" name="description" placeholder="Détails de votre demande" rows="6" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Créer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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

        <!-- ROOT: Create task form -->
        <?php if ($user['role'] === 'root'): ?>
            <div class="profile-card hidden" id="create-task-panel">
                <div class="profile-card__section">
                    <h2 class="profile-card__heading">Créer une nouvelle tâche:</h2>
                    <div class="profile-info">
                        <div class="profile-info__row">
                            <form method="POST" action="/TOnotDO/public/tasks/create">
                                <div style="margin-bottom: 12px;">
                                    <label for="task-title" style="display:block; margin-bottom:6px; font-weight:600;">Titre:</label>
                                    <input class="form-input" type="text" id="task-title" name="title" required>
                                </div>
                                <div style="margin-bottom: 12px;">
                                    <label for="task-desc" style="display:block; margin-bottom:6px; font-weight:600;">Description:</label>
                                    <textarea class="form-textarea" id="task-desc" name="description" rows="5"></textarea>
                                </div>
                                <div style="margin-bottom:12px;">
                                    <label for="assigned_to" style="display:block; margin-bottom:6px; font-weight:600;">Assigner à:</label>
                                    <select id="assigned_to" name="assigned_to" class="form-input">
                                        <option value="">-- Aucun --</option>
                                        <?php if (isset($workers)): ?>
                                            <?php foreach ($workers as $w): ?>
                                                <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['username']) ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Créer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROOT: Manage tasks panel -->
            <?php if (isset($tasks)): ?>
                <div class="profile-card hidden" id="manage-tasks-panel">
                    <div class="profile-card__section">
                        <h2 class="profile-card__heading">Gérer les tâches:</h2>
                        <div class="profile-info" style="max-height:420px; overflow-y:auto; padding-right:8px;">
                            <?php if (empty($tasks)): ?>
                                <p style="padding: 20px; text-align: center; color: #666;">Aucune tâche disponible.</p>
                            <?php else: ?>
                                <?php foreach ($tasks as $task): ?>
                                    <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 16px; background: #fafafa; transition: box-shadow 0.3s ease;"
                                         data-task-id="<?= $task['id'] ?>"
                                         data-task-title="<?= htmlspecialchars($task['title'], ENT_QUOTES) ?>"
                                         data-task-desc="<?= htmlspecialchars($task['description'], ENT_QUOTES) ?>"
                                         data-task-assigned="<?= htmlspecialchars($task['assigned_to_username'] ?? '', ENT_QUOTES) ?>"
                                         data-task-status="<?= htmlspecialchars($task['status'], ENT_QUOTES) ?>"
                                         class="task-card-item">
                                        
                                        <!-- Title and Status Header -->
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                            <h3 style="margin: 0; font-size: 18px; color: #333; font-weight: 600;">
                                                <?= htmlspecialchars($task['title']) ?>
                                            </h3>
                                            <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background-color: <?= $task['status'] === 'terminée' ? '#4CAF50' : '#9C27B0' ?>;">
                                                <?= htmlspecialchars($task['status']) ?>
                                            </span>
                                        </div>

                                        <!-- Date -->
                                        <div style="display: flex; align-items: center; margin-bottom: 16px; color: #666; font-size: 13px;">
                                            <span style="margin-right: 8px;">📅</span>
                                            <span>Créée le: <strong><?= htmlspecialchars($task['created_at']) ?></strong></span>
                                        </div>

                                        <!-- Description -->
                                        <div style="background: white; padding: 12px; border-radius: 6px; color: #555; line-height: 1.6; font-size: 14px; margin-bottom: 12px;">
                                            <?= nl2br(htmlspecialchars($task['description'])) ?>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div style="display: flex; gap: 8px;">
                                            <button class="btn btn-secondary btn-edit-task" type="button" data-task-id="<?= $task['id'] ?>">✏ Modifier</button>
                                            <button class="btn btn-danger btn-delete-task" type="button" data-task-id="<?= $task['id'] ?>">🗑 Supprimer</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- WORKER: My tasks panel -->
        <?php if ($user['role'] === 'worker' && isset($tasks)): ?>
            <div class="profile-card hidden" id="my-tasks-panel">
                <div class="profile-card__section">
                    <h2 class="profile-card__heading">Mes tâches:</h2>
                    <div class="profile-info" style="max-height:420px; overflow-y:auto; padding-right:8px;">
                        <?php if (empty($tasks)): ?>
                            <p style="padding: 20px; text-align: center; color: #666;">Aucune tâche assignée.</p>
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 16px; background: #fafafa; transition: box-shadow 0.3s ease;">
                                    <!-- Title and Status Header -->
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                        <h3 style="margin: 0; font-size: 18px; color: #333; font-weight: 600;">
                                            <?= htmlspecialchars($task['title']) ?>
                                        </h3>
                                        <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; color: white; background-color: <?= $task['status'] === 'terminée' ? '#4CAF50' : '#9C27B0' ?>;">
                                            <?= htmlspecialchars($task['status']) ?>
                                        </span>
                                    </div>

                                    <!-- Date -->
                                    <div style="display: flex; align-items: center; margin-bottom: 16px; color: #666; font-size: 13px;">
                                        <span style="margin-right: 8px;">📅</span>
                                        <span>Créée le: <strong><?= htmlspecialchars($task['created_at']) ?></strong></span>
                                    </div>

                                    <!-- Description -->
                                    <div style="background: white; padding: 12px; border-radius: 6px; color: #555; line-height: 1.6; font-size: 14px; margin-bottom: 12px;">
                                        <?= nl2br(htmlspecialchars($task['description'])) ?>
                                    </div>

                                    <!-- Action Button -->
                                    <div style="display: flex; gap: 8px;">
                                        <?php if ($task['status'] !== 'terminée'): ?>
                                            <button class="btn btn-primary btn-complete-task" type="button" data-task-id="<?= $task['id'] ?>">✔ Terminer</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
    </div>
</div>

<script>
(function(){
    const toggle = document.getElementById('profile-dropdown-toggle');
    const menu = document.getElementById('profile-dropdown-menu');

    if(toggle && menu){
        toggle.addEventListener('click', function(){
            const isOpen = menu.classList.contains('open');
            if(isOpen) {
                menu.classList.remove('open');
                menu.style.visibility = 'hidden';
                menu.style.opacity = '0';
            } else {
                menu.classList.add('open');
                menu.style.visibility = 'visible';
                menu.style.opacity = '1';
            }
            toggle.setAttribute('aria-expanded', !isOpen);
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e){
            if(!toggle.contains(e.target) && !menu.contains(e.target)){
                menu.classList.remove('open');
                menu.style.visibility = 'hidden';
                menu.style.opacity = '0';
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }
})();
</script>

<!-- Toast container for messages -->
<div id="toast-container" aria-live="polite" style="position:fixed; right:16px; bottom:16px; z-index:3000; display:flex; flex-direction:column; gap:8px;"></div>

<script>
// AJAX delete handler with confirmation, CSRF support, button disabling, DOM removal, and toasts
(function(){
    function showToast(message, isError){
        const container = document.getElementById('toast-container');
        const el = document.createElement('div');
        el.textContent = message;
        el.style.background = isError ? '#f44336' : '#4CAF50';
        el.style.color = 'white';
        el.style.padding = '10px 14px';
        el.style.borderRadius = '6px';
        el.style.boxShadow = '0 4px 12px rgba(0,0,0,0.12)';
        el.style.minWidth = '160px';
        container.appendChild(el);
        setTimeout(()=>{ el.style.opacity = '0'; setTimeout(()=>el.remove(), 300); }, 3000);
    }

    function getCsrfToken(){
        // Try meta tag
        const meta = document.querySelector('meta[name="csrf-token"]');
        if(meta) return meta.getAttribute('content');
        // Try global variable
        if(window.CSRF_TOKEN) return window.CSRF_TOKEN;
        // Not found
        return null;
    }

    document.addEventListener('click', function(e){
        if(!e.target.matches('.btn-delete-task')) return;
        const btn = e.target;
        const id = btn.dataset.taskId;
        if(!id) return;

        if(!confirm('Êtes-vous sûr?')) return;

        // disable button
        btn.disabled = true;
        const origText = btn.textContent;
        btn.textContent = 'Suppression...';

        const url = '/TOnotDO/public/tasks/delete/' + encodeURIComponent(id);
        const form = new FormData();
        // Include CSRF token if present
        const csrf = getCsrfToken();
        if(csrf) form.append('csrf_token', csrf);

        fetch(url, { method: 'POST', body: form, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(async res => {
                if(!res.ok) throw res;
                // Try to parse JSON if available
                let json = null;
                try{ json = await res.json(); }catch(e){}
                // If server returns {success:true} treat as success
                if(json && json.success === false) throw new Error('server');

                // remove card from DOM
                const card = btn.closest('.task-card-item');
                if(card) card.remove();
                showToast('Tâche supprimée', false);
            })
            .catch(async err => {
                console.error(err);
                showToast('Erreur lors de la suppression', true);
                btn.disabled = false;
                btn.textContent = origText;
            });
    });
})();
</script>

<script>
// Toggle panel visibility
(function () {
    const panels = [
        { btn: document.getElementById('btn-mes-demandes'), card: document.getElementById('mes-demandes-panel') },
        { btn: document.getElementById('btn-create-request'), card: document.getElementById('create-request-panel') },
        { btn: document.getElementById('btn-my-tasks'), card: document.getElementById('my-tasks-panel') },
        { btn: document.getElementById('btn-manage-tasks'), card: document.getElementById('manage-tasks-panel') },
        { btn: document.getElementById('btn-create-task'), card: document.getElementById('create-task-panel') },
        { btn: document.getElementById('btn-show-profile'), card: document.getElementById('profile-card') }
    ];

    panels.forEach(({ btn, card }) => {
        if (!btn || !card) return;

        btn.addEventListener('click', () => {
            // Find any currently visible panel
            const openPanel = panels.find(p => p.card && !p.card.classList.contains('hidden'));

            // If there is an open panel and it's not the one we're about to open, hide it first
            if (openPanel && openPanel.card !== card) {
                openPanel.card.classList.add('hidden');
                openPanel.btn?.setAttribute('aria-expanded', 'false');
            }

            // If the clicked button corresponds to the already-open panel, close it and stop
            if (openPanel && openPanel.card === card) {
                card.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                return;
            }

            // Show the requested card (after ensuring any previous card was closed)
            card.classList.remove('hidden');
            btn.setAttribute('aria-expanded', 'true');

            // Ensure all other panels are closed
            panels.forEach(p => {
                if (p.card !== card) {
                    p.card.classList.add('hidden');
                    p.btn?.setAttribute('aria-expanded', 'false');
                }
            });
        });
    });
})();
</script>

<!-- Edit Task Modal -->
<div id="edit-task-modal" class="hidden" style="position:fixed; inset:0; display:none; align-items:center; justify-content:center; background:rgba(0,0,0,0.4); z-index:2000;">
    <div style="background:#fff; padding:20px; border-radius:8px; width:90%; max-width:600px; box-shadow:0 6px 24px rgba(0,0,0,0.2); position:relative;">
        <button id="edit-modal-close" style="position:absolute; right:12px; top:12px; background:transparent; border:none; font-size:18px;">✕</button>
        <h2 style="margin-top:0;">Modifier la tâche</h2>
        <form id="edit-task-form">
            <input type="hidden" name="id" id="edit-task-id">
            <div style="margin-bottom:12px;">
                <label for="edit-task-title" style="display:block; font-weight:600; margin-bottom:6px;">Titre</label>
                <input id="edit-task-title" name="title" type="text" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            <div style="margin-bottom:12px;">
                <label for="edit-task-desc" style="display:block; font-weight:600; margin-bottom:6px;">Description</label>
                <textarea id="edit-task-desc" name="description" rows="6" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;"></textarea>
            </div>
            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <button type="button" id="edit-cancel" class="btn btn-secondary">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
// Edit modal behavior: open, populate, submit via AJAX, update DOM
(function(){
    const modal = document.getElementById('edit-task-modal');
    const closeBtn = document.getElementById('edit-modal-close');
    const cancelBtn = document.getElementById('edit-cancel');
    const form = document.getElementById('edit-task-form');

    function openModal(card){
        const id = card.dataset.taskId;
        document.getElementById('edit-task-id').value = id;
        document.getElementById('edit-task-title').value = card.dataset.taskTitle || '';
        document.getElementById('edit-task-desc').value = card.dataset.taskDesc || '';
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }

    function closeModal(){
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    document.addEventListener('click', function(e){
        if(e.target && e.target.matches('.btn-edit-task')){
            const btn = e.target;
            const card = btn.closest('.task-card-item');
            if(!card) return;
            openModal(card);
            // store reference for update
            form._targetCard = card;
        }
    });

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    form.addEventListener('submit', async function(ev){
        ev.preventDefault();
        const id = document.getElementById('edit-task-id').value;
        const title = document.getElementById('edit-task-title').value;
        const description = document.getElementById('edit-task-desc').value;

        const payload = new FormData();
        payload.append('title', title);
        payload.append('description', description);

        try{
            const res = await fetch('/TOnotDO/public/tasks/update/' + encodeURIComponent(id), {
                method: 'POST',
                body: payload
            });

            if(!res.ok){
                alert('Erreur lors de la mise à jour.');
                return;
            }

            // Update the DOM optimistically
            const card = form._targetCard;
            if(card){
                card.dataset.taskTitle = title;
                card.dataset.taskDesc = description;
                const h3 = card.querySelector('h3');
                if(h3) h3.textContent = title;
                const descEl = card.querySelector('.task-card-desc');
                if(descEl) descEl.innerHTML = description.replace(/\n/g, '<br>');
            }

            closeModal();
        }catch(err){
            console.error(err);
            alert('Erreur réseau. Réessayez.');
        }
    });

    // close modal on overlay click
    modal.addEventListener('click', function(e){
        if(e.target === modal) closeModal();
    });
})();
</script>

<script>
// AJAX complete handler for worker tasks
(function(){
    function getCsrfToken(){
        const meta = document.querySelector('meta[name="csrf-token"]');
        if(meta) return meta.getAttribute('content');
        if(window.CSRF_TOKEN) return window.CSRF_TOKEN;
        return null;
    }

    function showToast(message, isError){
        const container = document.getElementById('toast-container');
        if(!container) return;
        const el = document.createElement('div');
        el.textContent = message;
        el.style.background = isError ? '#f44336' : '#4CAF50';
        el.style.color = 'white';
        el.style.padding = '10px 14px';
        el.style.borderRadius = '6px';
        el.style.boxShadow = '0 4px 12px rgba(0,0,0,0.12)';
        el.style.minWidth = '160px';
        container.appendChild(el);
        setTimeout(()=>{ el.style.opacity = '0'; setTimeout(()=>el.remove(), 300); }, 3000);
    }

    document.addEventListener('click', function(e){
        if(!e.target.matches('.btn-complete-task')) return;
        const btn = e.target;
        const id = btn.dataset.taskId;
        if(!id) return;

        if(!confirm('Marquer comme terminée ?')) return;

        btn.disabled = true;
        const origText = btn.textContent;
        btn.textContent = 'Traitement...';

        const url = '/TOnotDO/public/tasks/complete/' + encodeURIComponent(id);
        const form = new FormData();
        const csrf = getCsrfToken();
        if(csrf) form.append('csrf_token', csrf);

        fetch(url, { method: 'POST', body: form, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(async res => {
                if(!res.ok) throw res;
                let json = null;
                try{ json = await res.json(); }catch(e){}

                const card = btn.closest('.task-card-item');
                if(card){
                    const statusEl = card.querySelector('.task-status');
                    if(statusEl){
                        statusEl.textContent = 'terminée';
                        statusEl.classList.remove('status-open');
                        statusEl.classList.add('status-terminée');
                        // try to update inline background if present
                        try{ statusEl.style.backgroundColor = '#4CAF50'; }catch(e){}
                    }
                    // remove the complete button
                    btn.remove();
                }

                showToast('Tâche marquée comme terminée', false);
            })
            .catch(err => {
                console.error(err);
                showToast('Erreur lors de la mise à jour', true);
                btn.disabled = false;
                btn.textContent = origText;
            });
    });
})();
</script>
